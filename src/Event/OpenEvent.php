<?php

namespace Teknasyon\AwsSesNotification\Event;

use Teknasyon\AwsSesNotification\Event\Constant\EventName;

class OpenEvent extends BaseEvent
{
    private array $open;
    private string $ipAddress;
    private string $userAgent;

    public function __construct($sesMessage)
    {
        parent::__construct($sesMessage);
        $this->setEventName(EventName::EVENT_NAME_OPEN);
        $this->open = $sesMessage['open'];
        $this->ipAddress = $this->open['ipAddress'];
        $this->timestamp = $this->open['timestamp'];
        $this->userAgent = $this->open['userAgent'];

        $this->setOpen(true);
    }

    public function getSesMessage(): mixed
    {
        return $this->open;
    }

    public function getIpAddress(): mixed
    {
        return $this->ipAddress;
    }

    public function getUserAgent(): mixed
    {
        return $this->userAgent;
    }

    public function getReceipts(): array
    {
        return $this->getMail()->getDestination();
    }
}