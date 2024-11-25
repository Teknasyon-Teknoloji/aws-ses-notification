<?php

namespace Teknasyon\AwsSesNotification\Event\Recipient;

class Recipient implements IRecipient
{
    private string $emailAddress;

    /**
     * @param array $recipient
     */
    public function __construct(array $recipient)
    {
        $this->emailAddress = $recipient['emailAddress'];
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }
}