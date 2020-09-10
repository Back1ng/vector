<?php


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
        $this->job      = $job;
    }

    /**
     * Расчет зарплаты на основе ранга
     *
     * @param int $rank
     * @return float|int
     */
    private function calculateByRank(int $rank)
    {
        switch ($rank) {
            case 1:
                return $this->job->getRate();
            case 2:
                return $this->job->getRate() * 1.25;
            case 3:
                return $this->job->getRate() * 1.5;
        }
        return false;
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
     * Получить ранг сотрудника
     *
     * @return int
     */
    public function getRank() : int
    {
        return $this->rank;
    }

    /**
     * Установить ранг сотрудника
     *
     * @param int $rank
     * @return $this
     */
    public function setRank(int $rank) : self
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Проверяет, является ли текущий сотрудник руководителем
     *
     * @return bool
     */
    public function isLeader() : bool
    {
        return $this->isLeader;
    }

    /**
     * Возвращает профессию сотрудника
     *
     * @return Job
     */
    public function getJob() : Job
    {
        return $this->job;
    }

    /**
     * Меняет лидера на не лидера, и наоборот
     *
     * @return $this
     */
    public function inverseLeader() : self
    {
        $this->isLeader = !$this->isLeader();

        return $this;
    }

    /**
     * Устанавливает новую работу
     *
     * @param Job $job
     * @param int $rank
     * @param bool $isLeader
     * @return $this
     */
    public function setJob(Job $job, int $rank, bool $isLeader = false) : self
    {
        $this->job      = $job;
        $this->rank     = $rank;
        $this->isLeader = $isLeader;

        return $this;
    }
}
