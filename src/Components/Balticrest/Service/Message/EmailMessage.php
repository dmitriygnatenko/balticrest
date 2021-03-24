<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Message;

class EmailMessage
{
    private string $email = '';

    private string $subject = '';

    private string $content = '';

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return EmailMessage
     */
    public function setEmail(string $email): EmailMessage
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return EmailMessage
     */
    public function setSubject(string $subject): EmailMessage
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return EmailMessage
     */
    public function setContent(string $content): EmailMessage
    {
        $this->content = $content;

        return $this;
    }
}