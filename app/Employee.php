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
     */
    private function calculateByRank(Rank $rank): float|int
    {
        $rate = $this->job->getRate();

        return $rate * $rank->getMultiplier();
    }

    /**
     * Получить зарплату для сотрудника
     */
    public function getRate(): float|int
    {
        $rate = $this->calculateByRank($this->getRank());

        if ($this->isLeader()) {
            $rate *= 1.5;
        }

        return $rate;
    }

    /**
     * Получить потребляемое кофе сотрудника
     */
    public function getCoffee(): int
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
        return $this->isLeader()
            ? 0
            : $this->job->getReport();
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

    public function isLeader(): bool
    {
        return $this->isLeader;
    }

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
