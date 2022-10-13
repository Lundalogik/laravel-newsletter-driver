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

    protected function newsletterTransport()
    {
        $config = $this->app['config']->get('services.newsletter', []);

        $client = new Client([
            'base_uri' => "{$config['base_url']}{$config['account']}/api/",
            'headers' => [
                'apikey' => $config['api_key'],
                'useremail' => $config['user_email'],
            ],
        ]);

        return new NewsletterTransport(
            new TransactionMail($client)
        );
    }
}
