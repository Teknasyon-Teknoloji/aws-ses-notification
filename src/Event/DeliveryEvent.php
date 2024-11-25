<?php

namespace Teknasyon\AwsSesNotification\Event;

use Teknasyon\AwsSesNotification\Event\Constant\EventName;
use Teknasyon\AwsSesNotification\Event\Recipient\Recipient;

class DeliveryEvent extends BaseEvent
{
    private array $delivery;
    private string $processingTimeMillis;
    private string $smtpResponse;
    private string $reportingMTA;
    /**
     * @var Recipient[] $recipients
     */
    private array $recipients;

    public function __construct($sesMessage)
    {
        parent::__construct($sesMessage);
        $this->setEventName(EventName::EVENT_NAME_DELIVERY);
        $this->delivery = $sesMessage['delivery'];
        $this->processingTimeMillis = $this->delivery['processingTimeMillis'];
        $this->recipients = $this->getDeliveryRecipients($this->delivery['recipients']);
        $this->smtpResponse = $this->delivery['smtpResponse'];
        $this->reportingMTA = $this->delivery['reportingMTA'];

        $this->setDelivery(true);
    }

    /**
     * @param array $recipients
     * @return array
     */
    private function getDeliveryRecipients(array $recipients): array
    {
        $bounceRecipients = [];
        foreach ($recipients as $recipient){
            $bounceRecipients[] = new Recipient(['emailAddress' => $recipient]);
        }

        return $bounceRecipients;
    }

    public function getReceipts(): array
    {
        return array_map(function ($recipient) {
            return $recipient->getEmailAddress();
        }, $this->recipients);
    }

    public function getSesMessage(): string
    {
        return $this->delivery;
    }

    public function getProcessingTimeMillis(): string
    {
        return $this->processingTimeMillis;
    }

    public function getSmtpResponse(): string
    {
        return $this->smtpResponse;
    }

    public function getReportingMTA(): string
    {
        return $this->reportingMTA;
    }
}