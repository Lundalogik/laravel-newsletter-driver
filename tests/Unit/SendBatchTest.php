<?php

namespace Lundalogik\NewsletterDriver\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Lundalogik\NewsletterDriver\Newsletter\SendTransactionMailArgs;
use Lundalogik\NewsletterDriver\Newsletter\SendTransactionMailBatchArgs;
use Lundalogik\NewsletterDriver\Newsletter\TransactionMail;

class SendBatchTest extends TestCase
{
    /**
     * Container for Guzzle Middleware
     *
     * @var mixed[]
     */
    protected $guzzleMiddlewareContainer = [];

    protected function exampleResponse(): string
    {
        return '{
            "SentTransactionMailModels": [
              {
                "TransactionMailId": 1,
                "RecipientName": "sample string 2",
                "RecipientEmail": "sample string 3",
                "SenderName": "sample string 4",
                "SenderEmail": "sample string 5",
                "FromName": "sample string 6",
                "FromEmail": "sample string 7",
                "ReplyTo": "sample string 8",
                "Subject": "sample string 9",
                "TrackOpenings": true,
                "TrackLinkClicks": true,
                "LinkBaseUrl": "sample string 12",
                "ExternalId": "sample string 13",
                "ExcludeTotalOptouts": true,
                "ExcludePublicationOptouts": true,
                "ExcludePreviousBounce": true,
                "IsInternalMail": true,
                "CreationDate": "2022-10-11T12:30:40.7910843+02:00"
              },
              {
                "TransactionMailId": 1,
                "RecipientName": "sample string 2",
                "RecipientEmail": "sample string 3",
                "SenderName": "sample string 4",
                "SenderEmail": "sample string 5",
                "FromName": "sample string 6",
                "FromEmail": "sample string 7",
                "ReplyTo": "sample string 8",
                "Subject": "sample string 9",
                "TrackOpenings": true,
                "TrackLinkClicks": true,
                "LinkBaseUrl": "sample string 12",
                "ExternalId": "sample string 13",
                "ExcludeTotalOptouts": true,
                "ExcludePublicationOptouts": true,
                "ExcludePreviousBounce": true,
                "IsInternalMail": true,
                "CreationDate": "2022-10-11T12:30:40.7910843+02:00"
              }
            ],
            "NotSentTransactionMailModels": [
              {
                "TransactionMailModel": {
                  "TransactionMailId": 1,
                  "RecipientName": "sample string 2",
                  "RecipientEmail": "sample string 3",
                  "SenderName": "sample string 4",
                  "SenderEmail": "sample string 5",
                  "FromName": "sample string 6",
                  "FromEmail": "sample string 7",
                  "ReplyTo": "sample string 8",
                  "Subject": "sample string 9",
                  "TrackOpenings": true,
                  "TrackLinkClicks": true,
                  "LinkBaseUrl": "sample string 12",
                  "ExternalId": "sample string 13",
                  "ExcludeTotalOptouts": true,
                  "ExcludePublicationOptouts": true,
                  "ExcludePreviousBounce": true,
                  "IsInternalMail": true,
                  "CreationDate": "2022-10-11T12:30:40.7910843+02:00"
                },
                "ErrorMessage": "sample string 1"
              },
              {
                "TransactionMailModel": {
                  "TransactionMailId": 1,
                  "RecipientName": "sample string 2",
                  "RecipientEmail": "sample string 3",
                  "SenderName": "sample string 4",
                  "SenderEmail": "sample string 5",
                  "FromName": "sample string 6",
                  "FromEmail": "sample string 7",
                  "ReplyTo": "sample string 8",
                  "Subject": "sample string 9",
                  "TrackOpenings": true,
                  "TrackLinkClicks": true,
                  "LinkBaseUrl": "sample string 12",
                  "ExternalId": "sample string 13",
                  "ExcludeTotalOptouts": true,
                  "ExcludePublicationOptouts": true,
                  "ExcludePreviousBounce": true,
                  "IsInternalMail": true,
                  "CreationDate": "2022-10-11T12:30:40.7910843+02:00"
                },
                "ErrorMessage": "sample string 1"
              }
            ]
        }';
    }

    protected function getMockGuzzleClient(): Client
    {
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $this->exampleResponse()
            ),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $history = Middleware::history($this->guzzleMiddlewareContainer);

        $handlerStack->push($history);

        $client = new Client([
            'handler' => $handlerStack,
            'base_uri' => 'https://qa.bwz.se/bedrock/mock-account/api/',
            'headers' => [
                'apikey' => 'mock-key',
                'useremail' => 'mock@email.com',
            ],
        ]);

        return $client;
    }

    public function test_it_sends_a_transaction_mail_using_the_sendbatch_endpoint(): void
    {
        $recipient = (new SendTransactionMailArgs())
            ->to('george.costanza@mail.com', 'George Costanza')
            ->from('art.vandelay@import-export.com', 'Art Vandelay')
            ->subject('Subject')
            ->textContent('Lorem ipsum dolor sit amet');

        $sendTransactionMailBatchArgs = new SendTransactionMailBatchArgs([$recipient]);

        $response = (new TransactionMail($this->getMockGuzzleClient()))
            ->sendBatch($sendTransactionMailBatchArgs);

        $request = $this->guzzleMiddlewareContainer[0]['request'];

        $this->assertEquals(
            'https://qa.bwz.se/bedrock/mock-account/api/transactionmail/sendbatch',
            (string) $request->getUri()
        );

        $this->assertEquals(
            (string) $request->getBody(),
            json_encode($sendTransactionMailBatchArgs->toArray())
        );

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals(
            $this->exampleResponse(),
            $response->getBody()->getContents()
        );
    }
}
