<?php declare(strict_types=1);

namespace App\Jobs;

use App\Job;

class Analyst extends Job
{
    /**
     * @var int
     */
    protected int $rate = 800;

    /**
     * @var int
     */
    protected int $coffee = 50;

    /**
     * @var int
     */
    protected int $report = 5;
}