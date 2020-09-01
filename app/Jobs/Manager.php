<?php

namespace App\Jobs;

use App\Job;

class Manager extends Job
{
    protected $rate   = 500;
    protected $coffee = 20;
    protected $report = 200;
}