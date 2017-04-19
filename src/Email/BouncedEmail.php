<?php

namespace Teknasyon\AwsSesNotification\Email;

class BouncedEmail extends BaseEmail
{
    private $bounceType;
    private $bounceSubType;
    private $bouncedRecipients;
    private $bounceMessage;

    public function __construct($sesMessage)
    {
        parent::__construct($sesMessage);

        $this->bounceMessage = $sesMessage['bounce'];
        $this->bounceType    = $sesMessage['bounce']['bounceType'];
        $this->bounceSubType = $sesMessage['bounce']['bounceSubType'];
        $this->bouncedRecipients = $sesMessage['bounce']['bouncedRecipients'];

        if ($this->bounceType=='Permanent') {
            $this->setShouldRemoved(true);
        }
        $this->setBounced(true);
    }

    public function getSesMessage()
    {
        return $this->bounceMessage;
    }

    public function getReceipts()
    {
        $receipts = [];
        foreach ($this->bouncedRecipients as $bouncedRecipient) {
            $receipts[] = $bouncedRecipient['emailAddress'];
        }
        return $receipts;
    }

}
