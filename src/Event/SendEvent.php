<?php

namespace Teknasyon\AwsSesNotification\Event;

use Teknasyon\AwsSesNotification\Event\Constant\EventName;

class SendEvent extends BaseEvent
{
    private array $send;

    public function __construct($sesMessage)
    {
        parent::__construct($sesMessage);
        $this->setEventName(EventName::EVENT_NAME_SEND);
        $this->send = $sesMessage['send'];

        $this->setSend(true);
    }

    public function getSesMessage(): array
    {
        return $this->send;
    }
}