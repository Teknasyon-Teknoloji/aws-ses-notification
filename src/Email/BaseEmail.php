<?php

namespace Teknasyon\AwsSesNotification\Email;

use Aws\Sns\Message;

abstract class BaseEmail implements IEmail
{
    private $sesMessage;
    private $bounced   = false;
    private $complaint = false;
    private $delivery  = false;
    private $shouldRemoved = false;
    private $messageId;
    private $commonHeaders;
    private $destination;
    private $source;
    private $sourceIp;

    /**
     * @param Message $snsMessage
     * @return BaseEmail
     * @throws \Exception
     */
    public static function factory($snsMessage)
    {
        //$sesMessage = json_decode($snsMessage['Message'], true);
        $sesMessage = $snsMessage['Message'];
        if (isset($sesMessage['notificationType'])===false) {
            throw new \Exception('SES "notificationType" not found! SNS MsgId: ' . $snsMessage['MessageId']);
        }

        $emailObj = null;
        if ($sesMessage['notificationType']=='Bounce' && isset($sesMessage['bounce'])) {
            $emailObj = new BouncedEmail($sesMessage);
        } elseif ($sesMessage['notificationType']=='Complaint' && isset($sesMessage['complaint'])) {
            $emailObj = new ComplaintEmail($sesMessage);
        } elseif ($sesMessage['notificationType']=='Delivery' ) {
            $emailObj = new DeliveryEmail($sesMessage);
        }
        if (!$emailObj) {
            throw new \Exception('SES "notificationType" not defined!'
                . ' SES Type: '. $sesMessage['notificationType']
                . ', SNS MsgId: ' . $snsMessage['MessageId']);
        }
        return $emailObj;
    }

    public function __construct($sesMessage)
    {
        $this->sesMessage    = $sesMessage;
        $this->source        = $sesMessage['mail']['source'];
        $this->sourceIp      = $sesMessage['mail']['sourceIp'];
        $this->messageId     = $sesMessage['mail']['messageId'];
        $this->commonHeaders = $sesMessage['mail']['commonHeaders'];
        $this->destination   = $sesMessage['mail']['destination'];
    }

    /**
     * @return bool
     */
    public function isBounced()
    {
        return $this->bounced;
    }

    /**
     * @param bool $isBounced
     */
    protected function setBounced($isBounced)
    {
        $this->bounced = boolval($isBounced);
    }

    /**
     * @return bool
     */
    public function isComplaint()
    {
        return $this->complaint;
    }

    /**
     * @param bool $isComplaint
     */
    protected function setComplaint($isComplaint)
    {
        $this->complaint = boolval($isComplaint);
    }

    /**
     * @return bool
     */
    public function isDelivery()
    {
        return $this->delivery;
    }

    /**
     * @param bool $isDelivery
     */
    protected function setDelivery($isDelivery)
    {
        $this->delivery = boolval($isDelivery);
    }

    /**
     * @return bool
     */
    public function shouldRemoved()
    {
        return $this->shouldRemoved;
    }

    /**
     * @param bool $shouldRemoved
     */
    protected function setShouldRemoved($shouldRemoved)
    {
        $this->shouldRemoved = boolval($shouldRemoved);
    }

    public function getSesMessage()
    {
        return null;
    }

    public function getReceipts()
    {
        return [];
    }

    /**
     * @return mixed
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @return mixed
     */
    public function getCommonHeaders()
    {
        return $this->commonHeaders;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getHeaders($name)
    {
        return isset($this->commonHeaders[$name])?$this->commonHeaders[$name]:null;
    }

    /**
     * @return array
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return mixed
     */
    public function getSourceIp()
    {
        return $this->sourceIp;
    }
}
