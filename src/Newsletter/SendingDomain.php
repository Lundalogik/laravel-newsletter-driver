<?php

namespace Lundalogik\NewsletterDriver\Newsletter;

use GuzzleHttp\Client;

class SendingDomain
{

    protected const URI = 'SendingDomain';

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
     * Get all sending domains
     */
    public function all(): array
    {
        $response = $this->httpClient->get(self::URI);
        
        $data = json_decode($response->getBody()->getContents());

        return $data;
    }

    public function validate(array $domain): bool
    {
        return $domain['IsDomainVerified'];
    }
}