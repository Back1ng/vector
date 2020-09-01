<?php

namespace App\Jobs;

use App\Job;

class Analyst extends Job
{
    /**
     * Зарплата для профессии
     *
     * @var int
     */
    protected int $rate = 800;

    /**
     * Расходумое количество кофе в литрах
     *
     * @var int
     */
    protected int $coffee = 50;

    /**
     * Производимое количество отчетов
     *
     * @var int
     */
    protected int $report = 5;
}