<?php

namespace Teknasyon\AwsSesNotification\Event;

use Teknasyon\AwsSesNotification\Event\Constant\EventName;

class RejectEvent extends BaseEvent
{
    private array $reject;
    private string $reason;

    public function __construct($sesMessage)
    {
        parent::__construct($sesMessage);
        $this->setEventName(EventName::EVENT_NAME_REJECT);
        $this->reject = $sesMessage['reject'];
        $this->reason = $this->reject['reason'];

        $this->setReject(true);
    }

    public function getSesMessage(): array
    {
        return $this->reject;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}