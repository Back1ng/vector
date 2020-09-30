<?php

namespace App\Jobs;

use App\Job;

class Marketer extends Job
{
    /**
     * @var int
     */
    protected int $rate = 400;

    /**
     * @var int
     */
    protected int $coffee = 15;

    /**
     * @var int
     */
    protected int $report = 150;
}