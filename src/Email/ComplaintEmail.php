<?php

namespace Teknasyon\AwsSesNotification\Email;

class ComplaintEmail extends BaseEmail
{
    private $complaintFeedbackType;
    private $complainedRecipients;
    private $complaintMessage;

    public function __construct($sesMessage)
    {
        parent::__construct($sesMessage);

        $this->complaintMessage = $sesMessage['complaint'];
        $this->complainedRecipients = $sesMessage['complaint']['complainedRecipients'];
        $this->complaintFeedbackType = isset($sesMessage['complaint']['complaintFeedbackType']) ? $sesMessage['complaint']['complaintFeedbackType'] : null;

        $this->setShouldRemoved(true);
        $this->setComplaint(true);
    }

    public function getSesMessage()
    {
        return $this->complaintMessage;
    }

    public function getReceipts()
    {
        $receipts = [];
        foreach ($this->complainedRecipients as $recipient) {
            $receipts[] = $recipient['emailAddress'];
        }
        return $receipts;
    }

}
