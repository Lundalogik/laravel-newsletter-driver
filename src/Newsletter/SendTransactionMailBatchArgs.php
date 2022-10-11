<?php

namespace Lundalogik\NewsletterDriver\Newsletter;

class SendTransactionMailBatchArgs
{
    /**
     * List of messages to send
     *
     * @var SendTransactionMailArgs[]
     */
    protected $sendTransactionMailArgs = [];

    /**
     * List of attachments to send
     *
     * @var AttachmentModel[]
     */
    protected $batchAttachmentsArgs = [];

    /**
     * Convert these batch messages to an array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'SendTransactionMailArgs' => array_map(function (SendTransactionMailArgs $arg) {
                return $arg->toArray();
            }, $this->sendTransactionMailArgs),

            'BatchAttachments'        => array_map(function (AttachmentModel $attachment) {
                return $attachment->toArray();
            }, $this->batchAttachmentsArgs),
        ];
    }

    /**
     * Create a new instance
     *
     * @param SendTransactionMailArgs[] $sendTransactionMailArgs
     * @param AttachmentModel[] $batchAttachmentArgs
     */
    public function __construct(array $sendTransactionMailArgs = [], array $batchAttachmentArgs = [])
    {
        $this->sendTransactionMailArgs = $sendTransactionMailArgs;

        $this->batchAttachmentsArgs = $batchAttachmentArgs;
    }
}
