<?php

namespace Teknasyon\AwsSesNotification\Event\Preferences;

class TopicPreferences
{
    private string $unsubscribeAll;
    /**
     * @var SubscriptionStatus[]
     */
    private array $topicSubscriptionStatus;

    /**
     * @param $topicReferences
     */
    public function __construct($topicReferences)
    {
        $this->unsubscribeAll = $topicReferences['unsubscribeAll'];
        $this->topicSubscriptionStatus = array_map(function ($subscriptionStatus){return new SubscriptionStatus($subscriptionStatus);}, $topicReferences['topicSubscriptionStatus']);
    }

    public function getUnsubscribeAll(): string
    {
        return $this->unsubscribeAll;
    }

    public function getTopicSubscriptionStatus(): array
    {
        return $this->topicSubscriptionStatus;
    }

}