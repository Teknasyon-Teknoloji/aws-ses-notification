<?php

namespace Teknasyon\AwsSesNotification;

use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use Teknasyon\AwsSesNotification\Email\BaseEmail;

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

        $emailObj = BaseEmail::factory($message);
        return $handler->process($emailObj);
    }
}
