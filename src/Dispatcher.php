<?php

namespace Teknasyon\AwsSesNotification;

use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use GuzzleHttp\Client;
use Teknasyon\AwsSesNotification\Email\BaseEmail;
use Teknasyon\AwsSesNotification\Email\Events\BaseEvent;
use Teknasyon\Src\Queue\Notification\EventType;

class Dispatcher
{
    /**
     * @param IHandler $handler
     * @param MessageValidator $validator
     * @return mixed
     * @throws \Exception
     */
    public static function handle(IHandler $handler, MessageValidator $validator)
    {
        $message = Message::fromRawPostData();
        $validator->validate($message);

        if ($message['Type'] === 'SubscriptionConfirmation') {
            // Send a request to the SubscribeURL to complete subscription
            (new Client())->get($message['SubscribeURL']);
            return $handler->snsSubsConfirmationReceived();
        }
        $decodedMessage = json_decode($message['Message'], true);


        if (isset($decodedMessage) && isset($decodedMessage['eventType'])) {
            $emailObj = BaseEvent::factory($message);
        } else {
            $emailObj = BaseEmail::factory($message);
        }

        return $handler->process($emailObj);
    }
}
