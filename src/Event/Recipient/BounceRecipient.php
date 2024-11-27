<?php

namespace Teknasyon\AwsSesNotification\Event\Recipient;

class BounceRecipient implements IRecipient
{
    private string $emailAddress;
    private ?string $action;
    private ?string $status;
    private ?string $diagnosticCode;

    /**
     * @param array $recipient
     */
    public function __construct(array $recipient)
    {
        $this->emailAddress = $recipient['emailAddress'];
        $this->action = $recipient['action'];
        $this->status = $recipient['status'];
        $this->diagnosticCode = $recipient['diagnosticCode'];
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getDiagnosticCode(): ?string
    {
        return $this->diagnosticCode;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }
}