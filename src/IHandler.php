<?php

namespace Teknasyon\AwsSesNotification;

use Teknasyon\AwsSesNotification\Email\IEmail;

interface IHandler
{
    public function process(IEmail $email);
}
