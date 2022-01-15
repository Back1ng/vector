<?php declare(strict_types=1);

use App\Employee;
use App\Jobs\Manager;
use PHPUnit\Framework\TestCase;

class EmployeeTest extends TestCase
{
    /**
     * @dataProvider employeeRateTestProvider
     */
    public function testGetRate(Employee $employee, int $rate)
    {
        $this->assertEquals($rate, $employee->getRate());
    }

    /**
     * @dataProvider employeeCoffeeTestProvider
     */
    public function testGetCoffee(Employee $employee, int $coffee)
    {
        $this->assertEquals($coffee, $employee->getCoffee());
    }

    /**
     * @dataProvider employeeReportsTestProvider
     */
    public function testGetReports(Employee $employee, int $reports)
    {
        $this->assertEquals($reports, $employee->getReport());
    }

    public function testRemoveLeadership()
    {
        $employee = new Employee(new Manager(), 1, true);

        $this->assertTrue($employee->isLeader());

        $employee->removeLeadership();

        $this->assertFalse($employee->isLeader());
    }

    public function testDefineAsLeader()
    {
        $employee = new Employee(new Manager(), 1);

        $this->assertFalse($employee->isLeader());

        $employee->defineAsLeader();

        $this->assertTrue($employee->isLeader());
    }

    public function employeeRateTestProvider(): array
    {
        return [
            [new Employee(new Manager(), 1), 500],
            [new Employee(new Manager(), 2), 625],
            [new Employee(new Manager(), 3), 750],
            [new Employee(new Manager(), 1, true), 750],
            [new Employee(new Manager(), 3, true), 1125],
        ];
    }

    public function employeeCoffeeTestProvider(): array
    {
        return [
            [new Employee(new Manager(), 1), 20],
            [new Employee(new Manager(), 2), 20],
            [new Employee(new Manager(), 3), 20],
            [new Employee(new Manager(), 1, true), 40],
            [new Employee(new Manager(), 3, true), 40],
        ];
    }

    public function employeeReportsTestProvider(): array
    {
        return [
            [new Employee(new Manager(), 1), 200],
            [new Employee(new Manager(), 2), 200],
            [new Employee(new Manager(), 3), 200],
            [new Employee(new Manager(), 1, true), 0],
            [new Employee(new Manager(), 3, true), 0],
        ];
    }
}