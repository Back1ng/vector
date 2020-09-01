<?php


namespace App;


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
     * Добавляет сотрудника в отдел
     *
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
     * Получить количество сотрудников в отделе
     *
     * @return array
     */
    public function getEmployees(): array
    {
        return $this->employees;
    }

    /**
     * Возвращает руководителя отдела
     *
     * @return Employee|false
     */
    public function getLeader()
    {
        foreach ($this->getEmployees() as $employee) {
            if ($employee->isLeader()) {
                return $employee;
            }
        }
        return false;
    }

    /**
     * Получить количество сотрудников в отделе
     *
     * @return int
     */
    public function getCountEmployee() : int
    {
        return count($this->employees);
    }

    /**
     * Получить расходы на зарплату
     *
     * @return int
     */
    public function getMoneyExpenses() : int
    {
        $money = 0;

        foreach ($this->employees as $employee) {
            $money += $employee->getRate();
        }

        return $money;
    }

    /**
     * Получить расходы на кофе
     *
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
     * Получить вырабатываемое количество отчетов в отделе
     *
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
     * Получить имя отдела
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Получить средний расход денег за страницу
     *
     * @return float
     */
    public function getAverageConsumptionMoneyPerPage() : float
    {
        return round($this->getMoneyExpenses() / $this->getReports(), 2);
    }

    /**
     * Увольняет сотрудника из отдела
     *
     * @param Employee $employee
     * @return $this
     */
    public function dismissEmployee(Employee $employee) : self
    {
        foreach ($this->employees as $id => $worker) {
            if ($worker === $employee) {
                unset($this->employees[$id]);
                break;
            }
        }

        return $this;
    }

    /**
     * Выбирает сотрудников по типу работы
     * Маркетинг, Аналитик и т.д.
     *
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
     * Получить руководителей отдела
     *
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
     * Получить сотрудников по работе и рангу
     *
     * @param Job $job
     * @param int $rank
     * @return array
     */
    public function getEmployeesByJobAndRank(Job $job, int $rank) : array
    {
        $data = [];

        foreach ($this->employees as $id => $employee) {
            if ($employee->getJob() instanceof $job && $employee->getRank() === $rank) {
                $data[] = $employee;
            }
        }

        return $data;
    }
}