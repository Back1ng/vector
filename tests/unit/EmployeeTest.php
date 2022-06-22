<?php declare(strict_types=1);

namespace unit;

use App\Employee;
use App\Jobs\Analyst;
use App\Jobs\Manager;
use App\Jobs\Marketer;
use App\ValueObjects\Ranks\MiddleRank;
use App\ValueObjects\Ranks\NewbeeRank;
use App\ValueObjects\Ranks\SeniorRank;
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
        $employee = new Employee(new Manager(), new NewbeeRank(), true);

        $this->assertTrue($employee->isLeader());

        $employee->removeLeadership();

        $this->assertFalse($employee->isLeader());
    }

    public function testRemoveLeadershipReturnEmployee()
    {
        $employee = new Employee(new Manager(), new NewbeeRank());

        $this->assertInstanceOf(Employee::class, $employee->removeLeadership());
    }

    public function testDefineAsLeader()
    {
        $employee = new Employee(new Manager(), new NewbeeRank());

        $this->assertFalse($employee->isLeader());

        $employee->defineAsLeader();

        $this->assertTrue($employee->isLeader());
    }

    public function testSetJob()
    {
        $employee = new Employee(new Manager(), new MiddleRank());

        $employee->setJob(new Analyst(), new SeniorRank(), true);
        $this->assertEquals('App\Jobs\Analyst', get_class($employee->getJob()));
        $this->assertEquals(3, $employee->getRank()->getValue());
        $this->assertEquals(true, $employee->isLeader());

        $employee->setJob(new Marketer(), new NewbeeRank());
        $this->assertEquals('App\Jobs\Marketer', get_class($employee->getJob()));
        $this->assertEquals(1, $employee->getRank()->getValue());
        $this->assertEquals(false, $employee->isLeader());
    }

    public function employeeRateTestProvider(): array
    {
        return [
            [new Employee(new Manager(), new NewbeeRank()), 500],
            [new Employee(new Manager(), new MiddleRank()), 625],
            [new Employee(new Manager(), new SeniorRank()), 750],
            [new Employee(new Manager(), new NewbeeRank(), true), 750],
            [new Employee(new Manager(), new SeniorRank(), true), 1125],
        ];
    }

    public function employeeCoffeeTestProvider(): array
    {
        return [
            [new Employee(new Manager(), new NewbeeRank()), 20],
            [new Employee(new Manager(), new MiddleRank()), 20],
            [new Employee(new Manager(), new SeniorRank()), 20],
            [new Employee(new Manager(), new NewbeeRank(), true), 40],
            [new Employee(new Manager(), new SeniorRank(), true), 40],
        ];
    }

    public function employeeReportsTestProvider(): array
    {
        return [
            [new Employee(new Manager(), new NewbeeRank()), 200],
            [new Employee(new Manager(), new MiddleRank()), 200],
            [new Employee(new Manager(), new SeniorRank()), 200],
            [new Employee(new Manager(), new NewbeeRank(), true), 0],
            [new Employee(new Manager(), new SeniorRank(), true), 0],
        ];
    }
}