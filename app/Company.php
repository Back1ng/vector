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
     * Добавление отдела в общий список
     *
     * @param Department $department
     * @return $this
     */
    public function addDepartment(Department $department) : self
    {
        $this->departments[] = $department;

        return $this;
    }

    /**
     * Получить количество сотрудников во всех отделах
     *
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
     * Получить расходы сотрудников на зарплату и кофе в каждом отделе
     *
     * @return array
     */
    public function getExpenses() : array
    {
        $rate = 0;
        $coffee = 0;

        foreach ($this->departments as $department) {
            $rate   += $department->getMoneyExpenses();
            $coffee += $department->getCoffeeExpenses();
        }

        return [$rate, $coffee];
    }

    /**
     * Получить средний расход зарплаты и кофе на отдел
     *
     * @return float[]|int[]
     */
    public function getAverageExpenses() : array
    {
        $rate = 0;
        $coffee = 0;

        foreach ($this->departments as $department) {
            $rate   += $department->getMoneyExpenses();
            $coffee += $department->getCoffeeExpenses();
        }

        return [
            $rate / count($this->getDepartments()),
            $coffee / count($this->getDepartments())
        ];
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
        $reports = 0;
        foreach ($this->departments as $department) {
            $reports += $department->getReports();
        }
        return $reports / count($this->getDepartments());
    }

    /**
     * Получить средний расход денег за страницу по отделам
     *
     * @return float
     */
    public function getAverageConsumptionMoneyPerPage() : float
    {
        $moneyConsumption = 0;
        foreach ($this->departments as $department) {
            $moneyConsumption += $department->getAverageConsumptionMoneyPerPage();
        }
        return $moneyConsumption / count($this->getDepartments());
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
     * Возвращает массив с отделами
     *
     * @return array
     */
    public function getDepartments() : array
    {
        return $this->departments;
    }

    /**
     * Получить среднее количество сотрудников по отделам
     *
     * @return float
     */
    public function getAverageCountEmployees() : float
    {
        $countEmployees  = 0;
        foreach ($this->departments as $department) {
            $countEmployees += $department->getCountEmployee();
        }
        return $countEmployees / count($this->getDepartments());
    }
}