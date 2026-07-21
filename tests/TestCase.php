<?php

namespace Lundalogik\NewsletterDriver\Tests;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Lundalogik\NewsletterDriver\NewsletterMailServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // additional setup ..
    }

    /**
     * @param mixed $app
     *
     * @return class-string[]
     */
    protected function getPackageProviders($app)
    {
        return [
            NewsletterMailServiceProvider::class,
        ];
    }

    /**
     * @param mixed $app
     *
     * @return void
     */
    protected function defineEnvironment($app): void
    {
        /** @var Application $app */
        /** @var ConfigRepository $config */
        $config = $app->make('config');

        $config->set('mail.default', 'newsletter');
        $config->set('mail.mailers.newsletter', [
            'transport' => 'newsletter',
            'api_key' => 'test-api-key',
            'user_email' => 'test@example.com',
            'account' => 'test-account',
            'base_url' => 'https://qa.bwz.se/bedrock/',
        ]);
        $config->set('services.newsletter', [
            'api_key' => 'test-api-key',
            'user_email' => 'test@example.com',
            'account' => 'test-account',
            'base_url' => 'https://qa.bwz.se/bedrock/',
        ]);
    }
}
