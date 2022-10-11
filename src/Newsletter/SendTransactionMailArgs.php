<?php

namespace Lundalogik\NewsletterDriver\Newsletter;

class SendTransactionMailArgs
{
    /**
     * The emails HTML content
     *
     * @var string
     */
    protected $htmlContent;

    /**
     * The emails text content
     *
     * @var string
     */
    protected $textContent;

    /**
     * The email recipients name
     *
     * @var string
     */
    protected $toName;

    /**
     * The email recipients email address
     *
     * @var string
     */
    protected $toEmail;

    /**
     * The senders name
     *
     * @var string
     */
    protected $fromName;

    /**
     * The senders email address
     *
     * @var string
     */
    protected $fromEmail;

    /**
     * The emails subject
     *
     * @var string
     */
    protected $subject;

    /**
     * Convert this TransactionMail to an array
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'HtmlContent'    => $this->htmlContent,
            'TextContent'    => $this->textContent,
            'RecipientName'  => $this->toName,
            'RecipientEmail' => $this->toEmail,
            'FromName'       => $this->fromName,
            'FromEmail'      => $this->fromEmail,
            'Subject'        => $this->subject,
        ];

        if (is_null($this->htmlContent)) {
            $data['TextContent'] = $this->textContent;
        } else {
            $data['HtmlContent'] = $this->htmlContent;
        }

        return $data;
    }

    /**
     * Set the recipient of this email
     *
     * @param string $email
     * @param string|null $name
     * @return self
     */
    public function to(string $email, string $name = null)
    {
        $this->toEmail = $email;

        $this->toName = $name;

        return $this;
    }

    /**
     * Set the sender of this message
     *
     * @param string $email
     * @param string|null $name
     * @return self
     */
    public function from(string $email, string $name = null)
    {
        $this->fromEmail = $email;

        $this->fromName = $name;

        return $this;
    }

    /**
     * Set the subject of this message
     *
     * @param string $subject
     * @return self
     */
    public function subject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set the HTML content of this message
     *
     * @param string $content
     * @return self
     */
    public function htmlContent(string $content)
    {
        $this->htmlContent = base64_encode($content);

        return $this;
    }

    /**
     * Set the text content of this message
     *
     * @param string $content
     * @return self
     */
    public function textContent(string $content)
    {
        $this->textContent = base64_encode($content);

        return $this;
    }
}
