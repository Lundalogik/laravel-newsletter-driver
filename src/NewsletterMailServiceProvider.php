<?php

namespace Lundalogik\NewsletterDriver;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Mail\MailServiceProvider;
use Lundalogik\NewsletterDriver\Newsletter\TransactionMail;
use Lundalogik\NewsletterDriver\Transport\NewsletterTransport;

class NewsletterMailServiceProvider extends MailServiceProvider
{
    /**
     * Register the Illuminate mailer instance.
     *
     * @return void
     */
    protected function registerIlluminateMailer()
    {
        parent::registerIlluminateMailer();

        try {
            app('mail.manager')->extend('newsletter', function () {
                return $this->newsletterTransport();
            });
        } catch (Exception $e) {
            // laravel 5.x
            app('swift.transport')->extend('newsletter', function () {
                return $this->newsletterTransport();
            });
        }
    }

    public function register()
    {
        // register a custom api class for newsletter, which can contain operations that are not related to mailing/backend work.
        // see here: https://stackoverflow.com/questions/45794683/how-to-create-aliases-in-laravel
        $this->app->singleton(NewsletterApi::class, function () {
            $client = $this->getHttpClient();
            return new NewsletterApi($client);
        });
        $this->app->alias(NewsletterApi::class, 'Newsletter');
    }

    protected function newsletterTransport(): NewsletterTransport
    {
        $client = $this->getHttpClient();

        return new NewsletterTransport(
            new TransactionMail($client)
        );
    }

    public function provides()
    {
        return [
            NewsletterApi::class,
            'Newsletter'
        ];
    }

    protected function getHttpClient(): Client
    {
        $config = $this->app['config']->get('services.newsletter', []);

        $client = new Client([
            'base_uri' => "{$config['base_url']}{$config['account']}/api/",
            'headers' => [
                'apikey' => $config['api_key'],
                'useremail' => $config['user_email'],
            ],
        ]);
        return $client;
    }
}
