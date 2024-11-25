<?php

namespace Teknasyon\AwsSesNotification\Event\Recipient;

interface IRecipient
{
    function getEmailAddress();
}