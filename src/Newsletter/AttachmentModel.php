<?php

namespace Lundalogik\NewsletterDriver\Newsletter;

class AttachmentModel
{
    /**
     * The attachments file data
     *
     * @var string
     */
    protected $fileData;

    /**
     * The attachments filename including extension
     *
     * @var string
     */
    protected $fileNameWithExtension;

    /**
     * The attachments mime type
     *
     * @var string
     */
    protected $mimeType;

    /**
     * Convert this AttachmentModel to an array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'FileData' => $this->fileData,
            'FileNameWithExtension' => $this->fileNameWithExtension,
            'MimeType' => $this->mimeType,
        ];
    }

    /**
     * Set the attachments file content
     *
     * @param string $contents  The attachment's file contents
     * @return self
     */
    public function fileData($contents)
    {
        $this->fileData = base64_encode($contents);

        return $this;
    }

    /**
     * Set the attachments filename with extension
     *
     * @param string $name  The filename of the attachment
     * @return self
     */
    public function fileNameWithExtension(string $name)
    {
        $this->fileNameWithExtension = $name;

        return $this;
    }

    /**
     * Set the attachmentss mime type
     *
     * @param string $mimeType  The mime type of the attachment
     * @return self
     */
    public function mimeType(string $mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }
}
