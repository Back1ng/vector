<?php


namespace unit;

use App\Company;
use App\Department;
use App\Employee;
use App\Jobs\Analyst;
use App\Jobs\Manager;
use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase
{
    public Company $company;

    public function setUp() : void
    {
        $this->company = new Company();

        $department = new Department('test');
        $department->addEmployee(new Employee(new Manager(), 1), 5);

        $department2 = new Department('test2');
        $department2->addEmployee(new Employee(new Analyst(), 1), 5);

        $this->company->addDepartment($department);
        $this->company->addDepartment($department2);
    }

    public function testGetMoneyExpenses()
    {
        $this->assertEquals(6500, $this->company->getMoneyExpenses());
    }

    public function testGetAverageConsumptionMoneyPerPage()
    {
        $this->company->getDepartments()[0]->getEmployees()[0]->getJob()->setRate(801);
        $this->assertEquals(407.005, $this->company->getAverageConsumptionMoneyPerPage());
    }

    public function testGetCountDepartments()
    {
        $this->assertEquals(2, $this->company->getCountDepartments());
    }

    public function testGetCountEmployee()
    {
        $this->assertEquals(10, $this->company->getCountEmployee());
    }

    public function testGetConsumptionMoneyPerPage()
    {
        $this->assertEquals(812.5, $this->company->getConsumptionMoneyPerPage());
    }

    public function testGetCoffeeExpenses()
    {
        $this->assertEquals(350, $this->company->getCoffeeExpenses());
    }

    public function testGetAverageMoneyExpenses()
    {
        $this->assertEquals(3250, $this->company->getAverageMoneyExpenses());
    }

    public function testGetReports()
    {
        $this->assertEquals(1025, $this->company->getReports());
    }

    public function testGetAverageReports()
    {
        $this->assertEquals(512.5, $this->company->getAverageReports());
    }

    public function testAddDepartment()
    {
        $this->assertEquals(2, $this->company->getCountDepartments());
        $this->company->addDepartment(new Department('test3'));
        $this->assertEquals(3, $this->company->getCountDepartments());
    }

    public function testGetDepartments()
    {
        $this->assertEquals(2, count($this->company->getDepartments()));
        $this->assertEquals('test2', $this->company->getDepartments()[1]->getName());
    }

    public function testGetAverageCountEmployees()
    {
        $this->assertEquals(5, $this->company->getAverageCountEmployees());
    }

    public function testGetAverageCoffeeExpenses()
    {
        $this->assertEquals(175, $this->company->getAverageCoffeeExpenses());
    }
}
