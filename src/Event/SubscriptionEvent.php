<?php

namespace Teknasyon\AwsSesNotification\Event;

use Teknasyon\AwsSesNotification\Event\Constant\EventName;
use Teknasyon\AwsSesNotification\Event\Preferences\TopicPreferences;

class SubscriptionEvent extends BaseEvent
{
    private array $subscription;
    private string $contactList;
    private string $source;
    private TopicPreferences $newTopicPreferences;
    private TopicPreferences $oldTopicPreferences;

    public function __construct($sesMessage)
    {
        parent::__construct($sesMessage);
        $this->setEventName(EventName::EVENT_NAME_SUBSCRIPTION);
        $this->subscription = $sesMessage['subscription'];
        $this->contactList = $this->subscription['contactList'];
        $this->source = $this->subscription['source'];
        $this->timestamp = $this->subscription['timestamp'];
        $this->newTopicPreferences = new TopicPreferences($this->subscription['newTopicPreferences']);
        $this->oldTopicPreferences = new TopicPreferences($this->subscription['oldTopicPreferences']);

        $this->setSubscription(true);
        $this->setUnsubscription($this->getNewTopicPreferences()->getUnsubscribeAll());
    }

    public function getReceipts(): array
    {
        return $this->getMail()->getDestination();
    }

    public function getSesMessage(): array
    {
        return $this->subscription;
    }

    public function getContactList(): string
    {
        return $this->contactList;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getNewTopicPreferences(): TopicPreferences
    {
        return $this->newTopicPreferences;
    }

    public function getOldTopicPreferences(): TopicPreferences
    {
        return $this->oldTopicPreferences;
    }
}