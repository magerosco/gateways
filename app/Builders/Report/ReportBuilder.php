<?php

namespace App\Builders\Report;

abstract class ReportBuilder implements ReportBuilderInterface
{
    protected string $title;
    protected array $data;

    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }
}
