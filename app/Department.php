<?php declare(strict_types=1);


namespace App;

use App\Exceptions\LeaderNotFoundException;
use App\ValueObjects\Ranks\Rank;

/**
 * Управление сотрудниками в отделе
 */
class Department
{
    /**
     * Список сотрудников в отделе
     *
     * @var array
     */
    protected array $employees = [];

    public function __construct(
        protected string $name
    )
    {
    }

    public function __clone()
    {
        foreach ($this->employees as $key => $employee) {
            $this->employees[$key] = clone $employee;
        }
    }

    /**
     * @param Employee $employee
     * @param int $count
     * @return $this
     */
    public function addEmployee(Employee $employee, int $count = 1) : self
    {
        $employee = serialize($employee);

        for ($i = 0; $i < $count; $i++) {
            $this->employees[] = unserialize($employee);
        }

        return $this;
    }

    /**
     * Получить список сотрудников в отделе
     *
     * @return array
     */
    public function getEmployees(): array
    {
        return $this->employees;
    }

    /**
     * @return int
     */
    public function getCountEmployee() : int
    {
        return count($this->employees);
    }

    /**
     * @return float
     */
    public function getMoneyExpenses() : float
    {
        return array_sum(array_map(
            fn(Employee $employee) => $employee->getRate(),
            $this->getEmployees(),
        ));
    }

    /**
     * @return int
     */
    public function getCoffeeExpenses() : int
    {
        return array_sum(array_map(
            fn(Employee $employee) => $employee->getCoffee(),
            $this->getEmployees(),
        ));
    }

    /**
     * @return float
     */
    public function getReports() : float
    {
        return array_sum(array_map(
            fn(Employee $employee) => $employee->getReport(),
            $this->getEmployees(),
        ));
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    public function getConsumptionMoneyPerPage(): float
    {
        return $this->getReports() === 0
            ? $this->getMoneyExpenses()
            : round($this->getMoneyExpenses() / $this->getReports(), 2);
    }

    /**
     * @return float
     */
    public function getAverageConsumptionMoneyPerPage() : float
    {
        if ($this->getCountEmployee() > 0) {
            return round($this->getMoneyExpenses() / $this->getReports(), 2);
        }

        return 0;
    }

    /**
     * @param Employee $employee
     * @return $this
     */
    public function dismissEmployee(Employee $employee) : self
    {
        if (is_int($id = array_search($employee, $this->employees))) {
            unset($this->employees[$id]);
        }

        $this->resetKeysEmployees();

        return $this;
    }

    /**
     * @param Job $job
     * @return array
     */
    public function getEmployeesByJob(Job $job) : array
    {
        return array_values(array_filter(
            $this->getEmployees(),
            fn(Employee $employee) => $employee->getJob() instanceof $job,
        ));
    }

    /**
     * @return Employee
     */
    public function getLeader() : Employee
    {
        foreach ($this->getEmployees() as $employee) {
            if ($employee->isLeader()) {
                return $employee;
            }
        }

        throw new LeaderNotFoundException();
    }

    /**
     * @return array
     */
    public function getLeaders() : array
    {
        return array_filter(
            $this->getEmployees(),
            fn(Employee $employee) => $employee->isLeader(),
        );
    }

    /**
     * @param Job $job
     * @param Rank $rank
     * @return array
     */
    public function getEmployeesByJobAndRank(Job $job, Rank $rank) : array
    {
        return array_values(array_filter(
            $this->getEmployeesByJob($job),
            fn(Employee $employee) => $employee->getRank()->getValue() === $rank->getValue()
        ));
    }

    private function resetKeysEmployees()
    {
        $this->employees = array_values($this->getEmployees());
    }
}
