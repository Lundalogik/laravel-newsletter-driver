<?php

namespace Lundalogik\NewsletterDriver\Newsletter;

use \GuzzleHttp\Psr7\Response;
use \GuzzleHttp\ClientInterface;
use \GuzzleHttp\Exception\GuzzleException;

class TransactionMail
{
    protected const URI = 'transactionmail';

    /**
     * HTTP Client.
     *
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * Create a new instance.
     *
     * @param ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Sends (or schedules) a batch of TransactionMailMessage.
     * Max 100 TransactionMailMessages per batch.
     *
     * @param SendTransactionMailBatchArgs $sendTransactionMailBatchArgs
     * @return Response
     * @throws GuzzleException
     */
    public function sendBatch(SendTransactionMailBatchArgs $sendTransactionMailBatchArgs)
    {
        $response = $this->httpClient->post(self::URI . '/sendbatch', [
            'json' => $sendTransactionMailBatchArgs->toArray(),
        ]);

        return $response;
    }
}
