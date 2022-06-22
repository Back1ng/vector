<?php declare(strict_types=1);


namespace App;

use App\ValueObjects\Ranks\Rank;

/**
 * Управление сотрудниками
 */
class Employee
{
    public function __construct(
        protected Job  $job,
        protected Rank $rank,
        protected bool $isLeader = false
    )
    {
        $this->job = new $job;
    }

    public function __clone()
    {
        $this->job = clone $this->job;
    }

    /**
     * Расчет зарплаты на основе ранга
     *
     * @param Rank $rank
     * @return float|int
     */
    private function calculateByRank(Rank $rank)
    {
        $rate = $this->job->getRate();

        return $rate * $rank->getMultiplier();
    }

    /**
     * Получить зарплату для сотрудника
     *
     * @return float|int
     */
    public function getRate()
    {
        $rate = $this->calculateByRank($this->getRank());

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
    public function getRank(): Rank
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     * @return $this
     */
    public function setRank(Rank $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLeader(): bool
    {
        return $this->isLeader;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
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
    public function defineAsLeader(): self
    {
        $this->isLeader = true;

        return $this;
    }

    /**
     * Устанавливает новую работу.
     *
     * @param Job $job
     * @param Rank $rank
     * @param bool $isLeader
     * @return $this
     */
    public function setJob(Job $job, Rank $rank, bool $isLeader = false): self
    {
        $this->job = new $job;
        $this->rank = $rank;
        $this->isLeader = $isLeader;

        return $this;
    }
}
