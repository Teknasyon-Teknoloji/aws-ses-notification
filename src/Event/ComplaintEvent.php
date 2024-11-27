<?php

namespace Teknasyon\AwsSesNotification\Event;

use Teknasyon\AwsSesNotification\Event\Constant\EventName;
use Teknasyon\AwsSesNotification\Event\Recipient\Recipient;

class ComplaintEvent extends BaseEvent
{
    private array $complaint;
    /**
     * @var Recipient[] $complainedRecipients
     */
    private array $complainedRecipients;
    private string $feedbackId;
    private ?string $userAgent;
    private ?string $complaintFeedbackType;
    private ?string $arrivalDate;

    public function __construct($sesMessage)
    {
        parent::__construct($sesMessage);
        $this->setEventName(EventName::EVENT_NAME_COMPLAINT);
        $this->complaint = $sesMessage['complaint'];
        $this->complainedRecipients = $this->getComplaintRecipients($this->complaint['complainedRecipients']);
        $this->userAgent = $this->complaint['userAgent'];
        $this->complaintFeedbackType = $this->complaint['complaintFeedbackType'];
        $this->feedbackId = $this->complaint['feedbackId'];
        $this->arrivalDate = $this->complaint['arrivalDate'];

        $this->setShouldRemoved(true);
        $this->setComplaint(true);
    }

    /**
     * @param array $recipients
     * @return array
     */
    private function getComplaintRecipients(array $recipients): array
    {
        $bounceRecipients = [];
        foreach ($recipients as $recipient){
            $bounceRecipients[] = new Recipient($recipient);
        }

        return $bounceRecipients;
    }

    public function getReceipts(): array
    {
        return array_map(function ($recipient) {
            return $recipient->getEmailAddress();
        }, $this->complainedRecipients);
    }

    public function getSesMessage(): array
    {
        return $this->complaint;
    }

    public function getComplainedRecipients(): array
    {
        return $this->complainedRecipients;
    }

    public function getFeedbackId(): string
    {
        return $this->feedbackId;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function getComplaintFeedbackType(): string
    {
        return $this->complaintFeedbackType;
    }

    public function getArrivalDate(): string
    {
        return $this->arrivalDate;
    }

}