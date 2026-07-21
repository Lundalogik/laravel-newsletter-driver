<?php

namespace Lundalogik\NewsletterDriver;

use GuzzleHttp\Client;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Lundalogik\NewsletterDriver\Newsletter\TransactionMail;
use Lundalogik\NewsletterDriver\Transport\NewsletterTransport;

class NewsletterMailServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        Mail::extend('newsletter', function (array $config = []) {
            return $this->newsletterTransport($config);
        });
    }

    /**
     * @return void
     */
    public function register()
    {
        // register a custom api class for newsletter, which can contain operations that are not related to mailing/backend work.
        // see here: https://stackoverflow.com/questions/45794683/how-to-create-aliases-in-laravel
        $this->app->singleton(NewsletterApi::class, function () {
            return new NewsletterApi($this->getHttpClient());
        });
    }

    /**
     * @param array<string, mixed> $config
     *
     * @return NewsletterTransport
     */
    protected function newsletterTransport(array $config = []): NewsletterTransport
    {
        return new NewsletterTransport(
            new TransactionMail($this->getHttpClient($config))
        );
    }

    /**
     * @return class-string[]
     */
    public function provides(): array
    {
        return [
            NewsletterApi::class,
        ];
    }

    /**
     * @param array<string, mixed> $config
     *
     * @return Client
     */
    protected function getHttpClient(array $config = []): Client
    {
        /** @var ConfigRepository $appConfig */
        $appConfig = $this->app->make('config');
        $serviceConfig = $appConfig->array('services.newsletter', []);

        $config = array_merge($serviceConfig, $config);

        $baseUrl = Arr::string($config, 'base_url');
        $account = Arr::string($config, 'account');
        $apiKey = Arr::string($config, 'api_key');
        $userEmail = Arr::string($config, 'user_email');

        return new Client([
            'base_uri' => "{$baseUrl}{$account}/api/",
            'headers' => [
                'apikey' => $apiKey,
                'useremail' => $userEmail,
            ],
        ]);
    }
}
