<?php

namespace Lundalogik\NewsletterDriver;

use GuzzleHttp\Client;
use Lundalogik\NewsletterDriver\Newsletter\SendingDomain;

class NewsletterApi
{

    protected $http;

    protected $domain;

    public function __construct(Client $http)
    {
        $this->http = $http;
        $this->domain = new SendingDomain($http);
    }

    public function domain(): SendingDomain
    {
        return $this->domain;
    }
}