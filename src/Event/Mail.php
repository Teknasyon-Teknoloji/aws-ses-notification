<?php

namespace Teknasyon\AwsSesNotification\Event;

class Mail
{
    private array $destination;
    private array $headers;
    private bool $headersTruncated;
    private string $messageId;
    private string $sendingAccountId;
    private string $source;
    private array $tags = [];

    /**
     * @param $mailArray
     */
    public function __construct($mailArray)
    {
        $this->destination = $this->extractEmailIfInBrackets($mailArray['destination']);
        $this->headers = $mailArray['headers'] ?? [];
        $this->headersTruncated = $mailArray['headersTruncated'];
        $this->messageId = $mailArray['messageId'];
        $this->sendingAccountId = $mailArray['sendingAccountId'];
        $this->source = $mailArray['source'];
        $this->tags = $mailArray['tags'];
    }

    public function getDestination(): array
    {
        return $this->destination;
    }

    public function setDestination(array $destination): void
    {
        $this->destination = $destination;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function isHeadersTruncated(): bool
    {
        return $this->headersTruncated;
    }

    public function setHeadersTruncated(bool $headersTruncated): void
    {
        $this->headersTruncated = $headersTruncated;
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function setMessageId(string $messageId): void
    {
        $this->messageId = $messageId;
    }

    public function getSendingAccountId(): string
    {
        return $this->sendingAccountId;
    }

    public function setSendingAccountId(string $sendingAccountId): void
    {
        $this->sendingAccountId = $sendingAccountId;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    private function extractEmailIfInBrackets(array $destinations): array
    {
        $this->destination = [];
        $pattern = '/<([^>]+)>/';
        $emailPattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';
        foreach ($destinations as $destination){
            if (preg_match($pattern, $destination, $matches)) {
                if (preg_match($emailPattern, $matches[1], $emailMatch)) {
                    $this->destination[] = $emailMatch[0];
                }
            } else {
                $this->destination[] = $destination;
            }
        }

        return $this->destination;
    }

}