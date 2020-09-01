<?php

namespace App\Jobs;

use App\Job;

class Engineer extends Job
{
    protected $rate   = 200;

    protected $coffee = 5;

    protected $report = 50;
}