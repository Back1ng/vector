<?php


namespace App;


abstract class Job
{
    public function setRate(int $rate) : self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getRate() : int
    {
        return $this->rate;
    }

    public function setCoffee(int $coffee) : self
    {
        $this->coffee = $coffee;

        return $this;
    }

    public function getCoffee() : int
    {
        return $this->coffee;
    }

    public function setReport(int $report) : self
    {
        $this->report = $report;

        return $this;
    }

    public function getReport() : int
    {
        return $this->report;
    }
}