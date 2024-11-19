<?php

namespace Teknasyon\AwsSesNotification\Event;

use Teknasyon\AwsSesNotification\Event\Constant\EventName;

class ClickEvent extends BaseEvent
{
    private array $click;
    private string $ipAddress;
    private string $link;
    private array $linkTags;
    private string $userAgent;
    public function __construct($sesMessage)
    {
        parent::__construct($sesMessage);
        $this->setEventName(EventName::EVENT_NAME_CLICK);
        $this->click = $sesMessage['click'];
        $this->ipAddress = $this->click['ipAddress'];
        $this->timestamp = $this->click['timestamp'];
        $this->userAgent = $this->click['userAgent'];
        $this->link = $this->click['link'];
        $this->linkTags = $this->click['linkTags'];

        $this->setClick(true);
    }

    public function getSesMessage(): array
    {
        return $this->click;
    }

    public function getIpAddress(): array
    {
        return $this->ipAddress;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getLinkTags(): string
    {
        return $this->linkTags;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }
}