<?php declare(strict_types=1);


namespace App;

use App\Exceptions\LeaderNotFoundException;

/**
 * Управление сотрудниками в отделе
 */
class Department
{
    /**
     * Название отдела
     *
     * @var string
     */
    protected string $name;

    /**
     * Список сотрудников в отделе
     *
     * @var array
     */
    protected array $employees = [];

    public function __construct(string $name)
    {
        $this->name = $name;
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
        $money = 0;

        foreach ($this->employees as $employee) {
            $money += $employee->getRate();
        }

        return $money;
    }

    /**
     * @return int
     */
    public function getCoffeeExpenses() : int
    {
        $coffee = 0;

        foreach ($this->employees as $employee) {
            $coffee += $employee->getCoffee();
        }

        return $coffee;
    }

    /**
     * @return float
     */
    public function getReports() : float
    {
        $reports = 0;

        foreach ($this->employees as $employee) {
            $reports += $employee->getReport();
        }

        return $reports;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    public function getConsumptionMoneyPerPage()
    {
        if ($this->getReports() === 0) {
            return $this->getMoneyExpenses();
        } else {
            return round($this->getMoneyExpenses() / $this->getReports(), 2);
        }
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
        $data = [];

        foreach ($this->employees as $employee) {
            if ($employee->getJob() instanceof $job) {
                $data[] = $employee;
            }
        }

        return $data;
    }

    /**
     * @return Employee
     */
    public function getLeader() : Employee
    {
        foreach ($this->employees as $employee) {
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
        $data = [];

        foreach ($this->employees as $employee) {
            if ($employee->isLeader()) {
                $data[] = $employee;
            }
        }

        return $data;
    }

    /**
     * @param Job $job
     * @param int $rank
     * @return array
     */
    public function getEmployeesByJobAndRank(Job $job, int $rank) : array
    {
        $data = [];

        foreach ($this->getEmployeesByJob($job) as $employee) {
            if ($employee->getRank() === $rank) {
                $data[] = $employee;
            }
        }

        return $data;
    }

    private function resetKeysEmployees()
    {
        $this->employees = array_values($this->getEmployees());
    }
}
