<?php

namespace Teknasyon\AwsSesNotification\Event\Constant;

class EventName
{
    const EVENT_NAME_BOUNCE = 'bounce';
    const EVENT_NAME_OPEN = 'open';
    const EVENT_NAME_COMPLAINT = 'complaint';
    const EVENT_NAME_DELIVERY = 'delivery';
    const EVENT_NAME_SEND = 'send';
    const EVENT_NAME_REJECT = 'reject';
    const EVENT_NAME_CLICK = 'click';
    const EVENT_NAME_RENDERING_FAILURE = 'rendering-failure';
    const EVENT_NAME_DELIVERY_DELAY = 'delivery-delay';
    const EVENT_NAME_SUBSCRIPTION = 'subscription';
}