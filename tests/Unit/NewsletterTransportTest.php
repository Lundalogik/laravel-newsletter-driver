<?php

namespace Lundalogik\NewsletterDriver\Tests\Unit;

use Lundalogik\NewsletterDriver\Newsletter\SendTransactionMailBatchArgs;
use Lundalogik\NewsletterDriver\Newsletter\TransactionMail;
use PHPUnit\Framework\TestCase;
use Lundalogik\NewsletterDriver\Transport\NewsletterTransport;
use Mockery;
use Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;

class NewsletterTransportTest extends TestCase
{

    /**
     * @throws PathAlreadyExists
     * @throws TransportExceptionInterface
     */
    public function test_it_can_send_full_email_with_attachments(): void
    {
        $tempDir = (new TemporaryDirectory())
            ->deleteWhenDestroyed()
            ->create();

        $tmpPath = $tempDir->path('test.txt');
        file_put_contents($tmpPath, 'this is a test');

        $message = new Email();
        $message->from('noreply@lime-forms.com')
            ->to('albin.hallen@lime.tech')
            ->subject('Test from laravel-newsletter-driver')
            ->html("<p>This is a test email from laravel-newsletter-driver</p>")
            ->attachFromPath($tmpPath, 'test.txt');

        $api = Mockery::mock(TransactionMail::class);

        /** @phpstan-ignore-next-line */
        $api->shouldReceive('sendBatch')
            ->once()
            ->with(Mockery::on(function ($args) use ($message) {
                $this->assertInstanceOf(SendTransactionMailBatchArgs::class, $args);

                $batchArgs = $args->toArray();
                $firstBatch = (object) $batchArgs['SendTransactionMailArgs'][0];

                $this->assertEquals($firstBatch->RecipientEmail, $message->getTo()[0]->getAddress());
                $this->assertEquals($firstBatch->RecipientName, $message->getTo()[0]->getName());
                $this->assertEquals($firstBatch->FromEmail, $message->getFrom()[0]->getAddress());
                $this->assertEquals($firstBatch->FromName, $message->getFrom()[0]->getName());
                $this->assertEquals($firstBatch->Subject, $message->getSubject());
                $this->assertNotEmpty($firstBatch->HtmlContent);

                $firstBatchAttachments = $batchArgs['BatchAttachments'];
                $this->assertEquals('test.txt', $firstBatchAttachments[0]['FileNameWithExtension']);

                return 1 === count($batchArgs['SendTransactionMailArgs']);
            }))->andReturn();

        /** @phpstan-ignore-next-line */
        (new NewsletterTransport($api))->send($message);
    }
}
