<?php declare(strict_types=1);


namespace App;

/**
 * Управление сотрудниками
 */
class Employee
{
    /**
     * Ранг сотрудника
     * (от 1 до 3)
     *
     * @var int
     */
    protected int $rank;

    /**
     * Руководитель отдела
     *
     * @var bool
     */
    protected bool $isLeader = false;

    /**
     * Профессия сотрудника
     *
     * @var Job
     */
    protected Job $job;

    public function __construct(Job $job, int $rank, bool $isLeader = false)
    {
        $this->rank     = $rank;
        $this->isLeader = $isLeader;
        $this->job      = new $job;
    }

    public function __clone()
    {
        $this->job = clone $this->job;
    }

    /**
     * Расчет зарплаты на основе ранга
     *
     * @param int $rank
     * @return float|int
     */
    private function calculateByRank(int $rank)
    {
        $rate = $this->job->getRate();

        switch ($rank) {
            case 1:
                return $rate;
            case 2:
                return $rate * 1.25;
            case 3:
                return $rate * 1.5;
            default:
                return 0;
        }
    }

    /**
     * Получить зарплату для сотрудника
     *
     * @return float|int
     */
    public function getRate()
    {
        $rank = $this->getRank();
        $rate = $this->calculateByRank($rank);

        if ($this->isLeader()) {
            $rate *= 1.5;
        }

        return $rate;
    }

    /**
     * Получить потребляемое кофе сотрудников
     *
     * @return int
     */
    public function getCoffee()
    {
        $coffee = $this->job->getCoffee();

        if ($this->isLeader()) {
            $coffee *= 2;
        }

        return $coffee;
    }

    /**
     * Получить количество отчетов
     *
     * @return int
     */
    public function getReport()
    {
        return $this->isLeader() ? 0 : $this->job->getReport();
    }

    /**
     * @return int
     */
    public function getRank() : int
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     * @return $this
     */
    public function setRank(int $rank) : self
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLeader() : bool
    {
        return $this->isLeader;
    }

    /**
     * @return Job
     */
    public function getJob() : Job
    {
        return $this->job;
    }

    public function removeLeadership()
    {
        $this->isLeader = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function defineAsLeader() : self
    {
        $this->isLeader = true;

        return $this;
    }

    /**
     * Устанавливает новую работу.
     *
     * @param Job $job
     * @param int $rank
     * @param bool $isLeader
     * @return $this
     */
    public function setJob(Job $job, int $rank, bool $isLeader = false) : self
    {
        $this->job = new $job;
        $this->rank = $rank;
        $this->isLeader = $isLeader;

        return $this;
    }
}
