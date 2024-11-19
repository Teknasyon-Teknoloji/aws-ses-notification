<?php

namespace Teknasyon\AwsSesNotification\Email;

interface IEmail
{
    public function isComplaint();
    public function isBounced();
    public function isDelivery();
    public function isClick();
    public function isOpen();
    public function isReject();
    public function isSend();
    public function isSubscription();
    public function isUnsubscription();
    public function shouldRemoved();
    public function getTags();
    public function getReceipts();
    public function getSesMessage();
}
