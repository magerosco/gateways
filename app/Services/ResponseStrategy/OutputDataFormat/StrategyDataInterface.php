<?php

namespace App\Services\ResponseStrategy\OutputDataFormat;

interface StrategyDataInterface
{
    public function getData(): array|object;
    public function getMessage(): string;
    public function getHttpResponse(): int;
}
