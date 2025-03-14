<?php

namespace App\Services\Report;

use App\Builders\Report\ReportBuilderInterface;

class ReportDirector
{
    public function buildReport(ReportBuilderInterface $builder, string $title, array $data)
    {
        return $builder->setTitle($title)
                       ->setData($data)
                       ->generate();
    }
}
