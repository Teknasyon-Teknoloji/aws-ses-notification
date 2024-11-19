<?php

namespace Teknasyon\AwsSesNotification\Event\Recipient;

class DeliveryDelayedRecipient implements IRecipient
{
    private string $emailAddress;
    private string $status;
    private string $diagnosticCode;

    /**
     * @param array $recipient
     */
    public function __construct(array $recipient)
    {
        $this->emailAddress = $recipient['emailAddress'];
        $this->status = $recipient['status'];
        $this->diagnosticCode = $recipient['diagnosticCode'];
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getDiagnosticCode(): string
    {
        return $this->diagnosticCode;
    }
}