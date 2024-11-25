<?php

namespace Teknasyon\AwsSesNotification\Email;

use Aws\Sns\Message;

abstract class BaseEmail implements IEmail
{
    private $sesMessage;
    private $bounced = false;
    private $complaint = false;
    private $delivery = false;
    private $shouldRemoved = false;
    private $messageId;
    private $commonHeaders = array();
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
        $sesMessage = json_decode($snsMessage['Message'], true);
        //$sesMessage = $snsMessage['Message'];
        if (!$sesMessage || is_array($sesMessage) === false || isset($sesMessage['notificationType']) === false) {
            throw new \Exception('SES "notificationType" not found! SNS MsgId: ' . $snsMessage['MessageId']);
        }

        $emailObj = null;
        if ($sesMessage['notificationType'] == 'Bounce' && isset($sesMessage['bounce'])) {
            $emailObj = new BouncedEmail($sesMessage);
        } elseif ($sesMessage['notificationType'] == 'Complaint' && isset($sesMessage['complaint'])) {
            $emailObj = new ComplaintEmail($sesMessage);
        } elseif ($sesMessage['notificationType'] == 'Delivery') {
            $emailObj = new DeliveryEmail($sesMessage);
        }
        if (!$emailObj) {
            throw new \Exception('SES "notificationType" not defined!'
                . ' SES Type: ' . $sesMessage['notificationType']
                . ', SNS MsgId: ' . $snsMessage['MessageId']);
        }
        return $emailObj;
    }

    public function __construct($sesMessage)
    {
        $this->sesMessage = $sesMessage;
        $this->source = self::parseEmail($sesMessage['mail']['source']);
        $this->sourceIp = isset($sesMessage['mail']['sourceIp']) ? $sesMessage['mail']['sourceIp'] : null;
        $this->messageId = $sesMessage['mail']['messageId'];
        $this->commonHeaders = isset($sesMessage['mail']['commonHeaders']) ? $sesMessage['mail']['commonHeaders'] : array();
        $this->destination = self::parseEmail($sesMessage['mail']['destination']);
    }

    public static function parseEmail($email)
    {
        if (is_array($email)) {
            foreach ($email as $i => $e) {
                if (strpos($e, '<')) {
                    list(, $e) = explode('<', $e);
                    $e = str_replace('>', '', trim($e));
                }
                $email[$i] = filter_var(filter_var($e, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
            }
            return $email;
        } else {
            if (strpos($email, '<')) {
                list(, $email) = explode('<', $email);
                $email = str_replace('>', '', trim($email));
            }
            return filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
        }
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
        return isset($this->commonHeaders[$name]) ? $this->commonHeaders[$name] : null;
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

    public function isClick(): bool
    {
        return false;
    }

    public function isOpen(): bool
    {
        return false;
    }

    public function isReject(): bool
    {
        return false;
    }

    public function isSend(): bool
    {
        return false;
    }

    public function isSubscription(): bool
    {
        return false;
    }

    public function isUnsubscription(): bool
    {
        return false;
    }

    public function getTags()
    {
        return [];
    }

}
