<?php

namespace App\Jobs;

use App\Job;

class Engineer extends Job
{
    /**
     * @var int
     */
    protected int $rate = 200;

    /**
     * @var int
     */
    protected int $coffee = 5;

    /**
     * @var int
     */
    protected int $report = 50;
}