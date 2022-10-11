<?php

namespace Lundalogik\NewsletterDriver\Transport;

use Swift_Mime_Attachment;
use Swift_Mime_SimpleMessage;
use Swift_TransportException;
use Illuminate\Mail\Transport\Transport;
use GuzzleHttp\Exception\GuzzleException;
use Lundalogik\NewsletterDriver\Newsletter\AttachmentModel;
use Lundalogik\NewsletterDriver\Newsletter\TransactionMail;
use Lundalogik\NewsletterDriver\Newsletter\SendTransactionMailArgs;
use Lundalogik\NewsletterDriver\Newsletter\SendTransactionMailBatchArgs;

class NewsletterTransport extends Transport
{
    /**
     * TransactionMail instance.
     *
     * @var TransactionMail
     */
    protected $api;

    /**
     * Create a new NewsletterTransport instance.
     *
     * @param  TransactionMail  $api
     * @return void
     */
    public function __construct(TransactionMail $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        try {
            $this->api->sendBatch(
                $this->getSendTransactionMailBatchArgs($message),
            );
        } catch (GuzzleException $e) {
            throw new Swift_TransportException('Request to Newsletter API failed.', $e->getCode(), $e);
        }

        $this->sendPerformed($message);

        return $this->numberOfRecipients($message);
    }

    /**
     * Get the SendTransactionMailBatchArgs from the message
     *
     * @param Swift_Mime_SimpleMessage $message
     * @return SendTransactionMailBatchArgs
     */
    protected function getSendTransactionMailBatchArgs(Swift_Mime_SimpleMessage $message)
    {
        $sendTransactionMailArgs = [];

        $from = $message->getFrom();

        [$fromEmail] = array_keys($from);
        [$fromName]  = array_values($from);

        foreach ($message->getTo() as $toEmail => $toName) {
            $sendTransactionMailArgs[] = (new SendTransactionMailArgs)
                ->to($toEmail, $toName)
                ->from($fromEmail, $fromName)
                ->subject($message->getSubject())
                ->htmlContent($message->getBody());
        }

        return new SendTransactionMailBatchArgs(
            $sendTransactionMailArgs,
            $this->buildAttachmentModels($message)
        );
    }

    /**
     * Get an array of AttachmentModel from the mssage
     *
     * @param Swift_Mime_SimpleMessage $message
     * @return AttachmentModel[]
     */
    protected function buildAttachmentModels(Swift_Mime_SimpleMessage $message)
    {
        return collect($message->getChildren())
            ->filter(function ($child) {
                return $child->getHeaders()->get('content-disposition') !== null;
            })
            ->map(function (Swift_Mime_Attachment $attachment) {
                return (new AttachmentModel)
                    ->fileData($attachment->getBody())
                    ->fileNameWithExtension($attachment->getFilename())
                    ->mimeType($attachment->getContentType());
            })
            ->toArray();
    }
}
