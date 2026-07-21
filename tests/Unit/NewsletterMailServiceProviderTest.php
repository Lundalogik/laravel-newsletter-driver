<?php

namespace Lundalogik\NewsletterDriver\Tests\Unit;

use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Mail;
use Lundalogik\NewsletterDriver\Tests\TestCase;
use Lundalogik\NewsletterDriver\Transport\NewsletterTransport;

class NewsletterMailServiceProviderTest extends TestCase
{
    /**
     * @return void
     */
    public function test_it_registers_the_newsletter_transport_with_the_mail_manager(): void
    {
        /** @var Mailer $mailer */
        $mailer = Mail::mailer('newsletter');
        $transport = $mailer->getSymfonyTransport();

        $this->assertInstanceOf(NewsletterTransport::class, $transport);
        $this->assertSame('newsletter', (string) $transport);
    }
}
