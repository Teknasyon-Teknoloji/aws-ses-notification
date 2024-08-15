<?php

namespace Teknasyon\AwsSesNotification\Email\Events;

class OpenEmail extends BaseEvent
{
    private $ipAddress;
    private $timestamp;
    private $userAgent;

    public function __construct($sesMessage)
    {
        parent::__construct($sesMessage);
        $this->setEventName('open');
        $this->ipAddress = $sesMessage['open']['ipAddress'];
        $this->timestamp = $sesMessage['open']['timestamp'];
        $this->userAgent = $sesMessage['open']['userAgent'];
    }

}