<?php

namespace Teknasyon\AwsSesNotification\Event;

use Teknasyon\AwsSesNotification\Event\Constant\EventName;
use Teknasyon\AwsSesNotification\Event\Recipient\DeliveryDelayedRecipient;

class DeliveryDelayEvent extends BaseEvent
{
    private array $deliveryDelay;
    private string $delayType;
    private string $expirationTime;
    /**
     * @var DeliveryDelayedRecipient[]
     */
    private array $delayedRecipients;

    public function __construct($sesMessage)
    {
        parent::__construct($sesMessage);
        $this->setEventName(EventName::EVENT_NAME_DELIVERY_DELAY);
        $this->deliveryDelay = $sesMessage['deliveryDelay'];
        $this->delayType = $this->deliveryDelay['delayType'];
        $this->expirationTime = $this->deliveryDelay['expirationTime'];
        $this->delayedRecipients = $this->getDeliveryDelayedRecipients($this->deliveryDelay['delayedRecipients']);

        $this->setDeliveryDelay(true);
    }

    public function getReceipts(): array
    {
        return array_map(function ($recipient) {
            return $recipient->getEmailAddress();
        }, $this->delayedRecipients);
    }

    private function getDeliveryDelayedRecipients(array $recipients): array
    {
        $bounceRecipients = [];
        foreach ($recipients as $recipient){
            $bounceRecipients[] = new DeliveryDelayedRecipient($recipient);
        }

        return $bounceRecipients;
    }

    public function getSesMessage(): array
    {
        return $this->deliveryDelay;
    }

    public function getDelayType(): string
    {
        return $this->delayType;
    }

    public function getExpirationTime(): string
    {
        return $this->expirationTime;
    }

    public function getDelayedRecipients(): array
    {
        return $this->delayedRecipients;
    }
}