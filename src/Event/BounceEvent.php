<?php

namespace Teknasyon\AwsSesNotification\Event;

use Teknasyon\AwsSesNotification\Event\Constant\EventName;
use Teknasyon\AwsSesNotification\Event\Recipient\BounceRecipient;

class BounceEvent extends BaseEvent
{
    private array $bounce;
    private string $bounceType;
    private string $bounceSubType;
    /**
     * @var BounceRecipient[] $bouncedRecipients
     */
    private array $bouncedRecipients;
    private string $feedbackId;
    private string $reportingMTA;

    public function __construct($sesMessage)
    {
        parent::__construct($sesMessage);
        $this->setEventName(EventName::EVENT_NAME_BOUNCE);
        $this->bounce = $sesMessage['bounce'];
        $this->bounceType = $this->bounce['bounceType'];
        $this->bounceSubType = $this->bounce['bounceSubType'];
        $this->bouncedRecipients = $this->getBounceRecipients($this->bounce['bouncedRecipients']);
        $this->timestamp = $this->bounce['timestamp'];
        $this->feedbackId = $this->bounce['feedbackId'];
        $this->reportingMTA = $this->bounce['reportingMTA'];

        if ($this->bounceType == 'Permanent') {
            $this->setShouldRemoved(true);
        }
        $this->setBounced(true);
    }

    /**
     * @param array $recipients
     * @return array
     */
    private function getBounceRecipients(array $recipients): array
    {
        $bounceRecipients = [];
        foreach ($recipients as $recipient){
            $bounceRecipients[] = new BounceRecipient($recipient);
        }

        return $bounceRecipients;
    }

    public function getReceipts(): array
    {
        return array_map(function ($recipient) {
            return $recipient->getEmailAddress();
        }, $this->bouncedRecipients);
    }

    public function getSesMessage(): array
    {
        return $this->bounce;
    }

    public function getBounceType(): string
    {
        return $this->bounceType;
    }

    public function getBounceSubType(): string
    {
        return $this->bounceSubType;
    }

    public function getBouncedRecipients(): array
    {
        return $this->bouncedRecipients;
    }

    public function getFeedbackId(): string
    {
        return $this->feedbackId;
    }

    public function getReportingMTA(): string
    {
        return $this->reportingMTA;
    }
}