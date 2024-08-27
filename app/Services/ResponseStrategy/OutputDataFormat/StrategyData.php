<?php

namespace App\Services\ResponseStrategy\OutputDataFormat;




class StrategyData implements StrategyDataInterface
{
    public function __construct(private array|object $data = [], private string $message = '', private int $response = 200)
    {
    }

    public function getData(): array|object
    {
        return $this->data;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getHttpResponse(): int
    {
        return $this->response;
    }
}
