<?php


namespace App;


class Company
{
    /**
     * Массив отделов компании
     *
     * @var array
     */
    private array $departments = [];

    /**
     * @param Department $department
     * @return $this
     */
    public function addDepartment(Department $department) : self
    {
        $this->departments[] = $department;

        return $this;
    }

    /**
     * @return int
     */
    public function getCountEmployee() : int
    {
        $data = 0;

        foreach ($this->departments as $department) {
            $data += $department->getCountEmployee();
        }

        return $data;
    }

    /**
     * @return int
     */
    public function getMoneyExpenses()
    {
        $rate = 0;

        foreach ($this->departments as $department) {
            $rate += $department->getMoneyExpenses();
        }

        return $rate;
    }

    /**
     * @return int
     */
    public function getCoffeeExpenses()
    {
        $coffee = 0;

        foreach ($this->departments as $department) {
            $coffee += $department->getCoffeeExpenses();
        }

        return $coffee;
    }

    public function getAverageMoneyExpenses()
    {
        $rate = $this->getMoneyExpenses();

        return $rate / $this->getCountDepartments();
    }

    public function getAverageCoffeeExpenses()
    {
        $coffee = $this->getCoffeeExpenses();

        return $coffee / $this->getCountDepartments();
    }

    public function getCountDepartments()
    {
        return count($this->departments);
    }

    /**
     * Получить количество отчетов во всех отделах
     *
     * @return float
     */
    public function getReports() : float
    {
        $reports = 0;

        foreach ($this->departments as $department) {
            $reports += $department->getReports();
        }

        return $reports;
    }

    /**
     * Получить среднее количество отчетов на отдел
     *
     * @return float
     */
    public function getAverageReports() : float
    {
        $reports = $this->getReports();

        return $reports / $this->getCountDepartments();
    }

    /**
     * Получить средний расход денег за страницу по отделам
     *
     * @return float
     */
    public function getAverageConsumptionMoneyPerPage() : float
    {
        $money = $this->getConsumptionMoneyPerPage();

        return $money / $this->getCountDepartments();
    }

    /**
     * Получить суммарный расход денег во всех отделах
     *
     * @return float
     */
    public function getConsumptionMoneyPerPage() : float
    {
        $moneyConsumption = 0;

        foreach ($this->departments as $department) {
            $moneyConsumption += $department->getAverageConsumptionMoneyPerPage();
        }

        return $moneyConsumption;
    }

    /**
     * @return array
     */
    public function getDepartments() : array
    {
        return $this->departments;
    }

    /**
     * @return float
     */
    public function getAverageCountEmployees() : float
    {
        $countEmployees = $this->getCountEmployee();

        return $countEmployees / $this->getCountDepartments();
    }
}