<?php

namespace Teknasyon\AwsSesNotification\Event;

use Aws\Sns\Message;
use Teknasyon\AwsSesNotification\Email\IEmail;

abstract class BaseEvent implements IEmail
{
    private string $name;
    protected string $timestamp;
    private $bounced = false;
    private $complaint = false;
    private $click = false;
    private $open = false;
    private $reject = false;
    private $send = false;
    private $subscription = false;
    private $unsubscription = false;
    private $deliveryDelay = false;
    private $delivery = false;
    private $renderingFailure = false;
    private $shouldRemoved = false;
    private Mail $mail;

    public function __construct($sesMessage)
    {
        $this->mail = new Mail($sesMessage['mail']);
    }

    public function isSubscription(): bool
    {
        return $this->subscription;
    }

    public function setSubscription(bool $subscription): void
    {
        $this->subscription = $subscription;
    }

    protected function setEventName($eventName){
        $this->name = $eventName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTags(): array
    {
        return $this->mail->getTags();
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @param Message $snsMessage
     * @return BaseEvent
     * @throws \Exception
     */
    public static function factory($snsMessage)
    {
        $sesMessage = json_decode($snsMessage['Message'], true);
        if (!$sesMessage || is_array($sesMessage) === false || isset($sesMessage['eventType']) === false) {
            throw new \Exception('SES "eventType" not found! SNS MsgId: ' . $snsMessage['MessageId'] . ' t '. json_encode($snsMessage));
        }

        $emailObj = null;
        switch ($sesMessage['eventType']){
            case 'Open':
                $emailObj = new OpenEvent($sesMessage);
                break;
            case 'Bounce':
                $emailObj = new BounceEvent($sesMessage);
                break;
            case 'Complaint':
                $emailObj = new ComplaintEvent($sesMessage);
                break;
            case 'Delivery':
                $emailObj = new DeliveryEvent($sesMessage);
                break;
            case 'Send':
                $emailObj = new SendEvent($sesMessage);
                break;
            case 'Reject':
                $emailObj = new RejectEvent($sesMessage);
                break;
            case 'Click':
                $emailObj = new ClickEvent($sesMessage);
                break;
            case 'Rendering Failure':
                $emailObj = new RenderingFailureEvent($sesMessage);
                break;
            case 'DeliveryDelay':
                $emailObj = new DeliveryDelayEvent($sesMessage);
                break;
            case 'Subscription':
                $emailObj = new SubscriptionEvent($sesMessage);
                break;
            default:
                throw new \Exception('SES "eventType" not defined!'
                    . ' SES Type: ' . $sesMessage['eventType']
                    . ', SNS MsgId: ' . $snsMessage['MessageId']);
        }

        return $emailObj;
    }

    public function isBounced(): bool
    {
        return $this->bounced;
    }

    public function setBounced(bool $bounced): void
    {
        $this->bounced = $bounced;
    }

    public function isComplaint(): bool
    {
        return $this->complaint;
    }

    public function setComplaint(bool $complaint): void
    {
        $this->complaint = $complaint;
    }

    public function isDelivery(): bool
    {
        return $this->delivery;
    }

    public function setDelivery(bool $delivery): void
    {
        $this->delivery = $delivery;
    }

    public function shouldRemoved(): bool
    {
        return $this->shouldRemoved;
    }

    public function setShouldRemoved(bool $shouldRemoved): void
    {
        $this->shouldRemoved = $shouldRemoved;
    }

    public function isClick(): bool
    {
        return $this->click;
    }

    public function setClick(bool $click): void
    {
        $this->click = $click;
    }

    public function isOpen(): bool
    {
        return $this->open;
    }

    public function setOpen(bool $open): void
    {
        $this->open = $open;
    }

    public function isReject(): bool
    {
        return $this->reject;
    }

    public function setReject(bool $reject): void
    {
        $this->reject = $reject;
    }

    public function isSend(): bool
    {
        return $this->send;
    }

    public function setSend(bool $send): void
    {
        $this->send = $send;
    }

    public function isDeliveryDelay(): bool
    {
        return $this->deliveryDelay;
    }

    public function setDeliveryDelay(bool $deliveryDelay): void
    {
        $this->deliveryDelay = $deliveryDelay;
    }

    public function isRenderingFailure(): bool
    {
        return $this->renderingFailure;
    }

    public function setRenderingFailure(bool $renderingFailure): void
    {
        $this->renderingFailure = $renderingFailure;
    }

    public function getMail(): Mail
    {
        return $this->mail;
    }

    public function isUnsubscription()
    {
        return $this->unsubscription;
    }

    public function setUnsubscription(bool $unsubscription): void
    {
        $this->unsubscription = $unsubscription;
    }

    public function getSesMessage()
    {
        return [];
    }

    public function getReceipts(): array
    {
        return [];
    }

}