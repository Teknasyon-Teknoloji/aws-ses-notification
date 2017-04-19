<?php

namespace Teknasyon\AwsSesNotification\Email;

interface IEmail
{
    public function isComplaint();
    public function isBounced();
    public function isDelivery();
    public function shouldRemoved();
    public function getReceipts();
    public function getSesMessage();
}
