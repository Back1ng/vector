<?php declare(strict_types=1);

namespace App\Jobs;

use App\Job;

class Manager extends Job
{
    /**
     * @var int
     */
    protected int $rate = 500;

    /**
     * @var int
     */
    protected int $coffee = 20;

    /**
     * @var int
     */
    protected int $report = 200;
}