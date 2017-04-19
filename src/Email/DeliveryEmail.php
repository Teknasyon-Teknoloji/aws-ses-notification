<?php

namespace Teknasyon\AwsSesNotification\Email;

class DeliveryEmail extends BaseEmail
{
    public function __construct($msg)
    {
        parent::__construct($msg);
        $this->setDelivery(true);
    }
}
