<?php

namespace Lundalogik\NewsletterDriver\Transport;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Lundalogik\NewsletterDriver\Newsletter\AttachmentModel;
use Lundalogik\NewsletterDriver\Newsletter\SendTransactionMailArgs;
use Lundalogik\NewsletterDriver\Newsletter\SendTransactionMailBatchArgs;
use Lundalogik\NewsletterDriver\Newsletter\TransactionMail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mailer\SentMessage;

class NewsletterTransport extends AbstractTransport
{
    /**
     * TransactionMail instance.
     *
     * @var TransactionMail
     */
    protected TransactionMail $api;

    /**
     * Create a new NewsletterTransport instance.
     *
     * @param  TransactionMail  $api
     * @return void
     */
    public function __construct(TransactionMail $api)
    {
        $this->api = $api;

        parent::__construct();
    }

    /**
     * Send the given message.
     * Triggered by parent::send()
     *
     * @param SentMessage $message
     * @return void
     */
    protected function doSend(SentMessage $message): void
    {
        try {
            $originalMessage = $message->getOriginalMessage();
            if ($originalMessage instanceof Email) {
                $this->api->sendBatch(
                    $this->getSendTransactionMailBatchArgs($message->getEnvelope(), $originalMessage)
                );
            }
        } catch (GuzzleException $e) {
            throw new TransportException(
                'Request to Newsletter API failed.',
                $e->getCode(),
                new Exception($e)
            );
        }
    }


    /**
     * Get the SendTransactionMailBatchArgs from the message
     *
     * @param Envelope $envelope
     * @param Email $message
     * @return SendTransactionMailBatchArgs
     */
    protected function getSendTransactionMailBatchArgs(Envelope $envelope, Email $message): SendTransactionMailBatchArgs
    {
        $sendTransactionMailArgs = [];

        $fromEmail = $envelope->getSender()->getAddress();
        $fromName = $envelope->getSender()->getName();

        foreach ($envelope->getRecipients() as $index => $to) {
            $sendTransactionMailArgs[] = (new SendTransactionMailArgs())
                ->to($to->getAddress(), $to->getName())
                ->from($fromEmail, $fromName)
                ->subject($message->getSubject())
                ->htmlContent($message->getHtmlBody());
        }

        return new SendTransactionMailBatchArgs(
            $sendTransactionMailArgs,
            $this->buildAttachmentModels($message)
        );
    }

    /**
     * Get an array of AttachmentModel from the message
     *
     * @param Email $message
     * @return AttachmentModel[]
     */
    protected function buildAttachmentModels(Email $message): array
    {
        return collect($message->getAttachments())
            ->filter(function (DataPart $child) {
                return $child->getPreparedHeaders()->get('Content-Disposition') !== null;
            })
            ->map(function (DataPart $attachment) {
                return (new AttachmentModel())
                    ->fileData($attachment->getBody())
                    ->fileNameWithExtension($attachment->getFilename())
                    ->mimeType($attachment->getContentType());
            })
            ->toArray();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "newsletter";
    }
}
