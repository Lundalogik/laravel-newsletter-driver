<?php

namespace Lundalogik\NewsletterDriver\Newsletter;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

/**
 * @template-covariant Response
 */
class TransactionMail
{
    protected const URI = 'transactionmail';

    /**
     * HTTP Client.
     *
     * @var Client
     */
    protected $httpClient;

    /**
     * Create a new instance.
     *
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
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
