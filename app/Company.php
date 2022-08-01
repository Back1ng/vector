<?php declare(strict_types=1);

namespace App;

class Company
{
    /**
     * Массив отделов компании
     *
     * @var array
     */
    private array $departments = [];

    public function __clone()
    {
        foreach ($this->departments as $key => $department) {
            $this->departments[$key] = clone $department;
        }
    }

    /**
     * @return $this
     */
    public function addDepartment(Department $department) : self
    {
        $this->departments[] = $department;

        return $this;
    }

    public function getCountEmployee(): int
    {
        return array_sum(
            array_map(
                fn(Department $department) => $department->getCountEmployee(),
                $this->getDepartments(),
            )
        );
    }

    public function getMoneyExpenses(): int|float
    {
        return array_sum(
            array_map(
                fn(Department $department) => $department->getMoneyExpenses(),
                $this->getDepartments(),
            )
        );
    }

    public function getCoffeeExpenses(): int
    {
        return array_sum(
            array_map(
                fn(Department $department) => $department->getCoffeeExpenses(),
                $this->getDepartments(),
            )
        );
    }

    public function getAverageMoneyExpenses(): float|int
    {
        $rate = $this->getMoneyExpenses();

        return $rate / $this->getCountDepartments();
    }

    public function getAverageCoffeeExpenses(): float|int
    {
        $coffee = $this->getCoffeeExpenses();

        return $coffee / $this->getCountDepartments();
    }

    public function getCountDepartments(): int
    {
        return count($this->departments);
    }

    /**
     * Получить количество отчетов во всех отделах
     */
    public function getReports() : float
    {
        return array_sum(
            array_map(
                fn(Department $department) => $department->getReports(),
                $this->getDepartments(),
            )
        );
    }

    /**
     * Получить среднее количество отчетов на отдел
     */
    public function getAverageReports() : float
    {
        $reports = $this->getReports();

        return $reports / $this->getCountDepartments();
    }

    /**
     * Получить средний расход денег за страницу по отделам
     */
    public function getAverageConsumptionMoneyPerPage() : float
    {
        return round($this->getMoneyExpenses() / $this->getReports(), 2);
    }

    /**
     * Получить суммарный расход денег во всех отделах
     */
    public function getConsumptionMoneyPerPage() : float
    {
        return array_sum(
            array_map(
                fn(Department $department) => $department->getConsumptionMoneyPerPage(),
                $this->getDepartments(),
            ),
        );
    }

    public function getDepartments() : array
    {
        return $this->departments;
    }

    public function getAverageCountEmployees() : float
    {
        return $this->getCountEmployee() / $this->getCountDepartments();
    }
}