<?php

namespace App\Services\ResponseStrategy;

interface StrategyDataInterface
{
    public function getData(): array|object;
    public function getMessage(): string;
    public function getHttpResponse(): int;
}
