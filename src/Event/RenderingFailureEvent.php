<?php

namespace Teknasyon\AwsSesNotification\Event;

use Teknasyon\AwsSesNotification\Event\Constant\EventName;

class RenderingFailureEvent extends BaseEvent
{
    private array $renderingFailure;
    private string $errorMessage;
    private string $templateName;

    public function __construct($sesMessage)
    {
        parent::__construct($sesMessage);
        $this->setEventName(EventName::EVENT_NAME_RENDERING_FAILURE);
        $this->renderingFailure = $sesMessage['failure'];
        $this->errorMessage = $this->renderingFailure['errorMessage'];
        $this->templateName = $this->renderingFailure['templateName'];

        $this->setRenderingFailure(true);
    }

    public function getSesMessage(): array
    {
        return $this->renderingFailure;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getTemplateName(): string
    {
        return $this->templateName;
    }

}