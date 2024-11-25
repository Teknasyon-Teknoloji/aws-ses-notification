<?php

namespace Teknasyon\AwsSesNotification\Event\Preferences;

class SubscriptionStatus
{
    private string $topicName;
    private string $subscriptionStatus;

    /**
     * @param $topicStatus
     */
    public function __construct($topicStatus)
    {
        $this->topicName = $topicStatus['topicName'];
        $this->subscriptionStatus = $topicStatus['subscriptionStatus'];
    }

    public function getTopicName(): mixed
    {
        return $this->topicName;
    }

    public function getSubscriptionStatus(): mixed
    {
        return $this->subscriptionStatus;
    }

}