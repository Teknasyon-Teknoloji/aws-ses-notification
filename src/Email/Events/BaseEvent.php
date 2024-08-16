<?php

namespace Teknasyon\AwsSesNotification\Email\Events;

use Aws\Sns\Message;
use Teknasyon\AwsSesNotification\Email\BaseEmail;

class BaseEvent extends BaseEmail
{
    private string $name;
    private array $destination;
    private array $tags;
    public function __construct($sesMessage)
    {
        parent::__construct($sesMessage);
        if(isset($sesMessage['mail'])){
            $this->tags = isset($sesMessage['mail']['tags']) ? $sesMessage['mail']['tags'] : [];
            $this->destination = isset($sesMessage['mail']['destination']) ? $sesMessage['mail']['destination'] : [];
        }else{
            $this->tags = [];
            $this->destination = [];
        }
    }

    protected function setEventName($eventName){
        $this->name = $eventName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTags()
    {
        return $this->tags;
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
        if (isset($sesMessage['eventType']) == 'Open'){
            $emailObj = new OpenEmail($sesMessage);
        }

        if (!$emailObj) {
            throw new \Exception('SES "eventType" not defined!'
                . ' SES Type: ' . $sesMessage['eventType']
                . ', SNS MsgId: ' . $snsMessage['MessageId']);
        }
        return $emailObj;
    }

    public function getReceipts()
    {
        return $this->destination;
    }
}