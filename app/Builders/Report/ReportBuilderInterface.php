<?php

namespace App\Builders\Report;

interface ReportBuilderInterface
{
    public function setTitle(string $title);
    public function setData(array $data);
    public function generate();
}

