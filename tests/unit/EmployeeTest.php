<?php declare(strict_types=1);

namespace unit;

use App\Employee;
use App\Exceptions\InvalidRankException;
use App\Jobs\Analyst;
use App\Jobs\Manager;
use App\Jobs\Marketer;
use App\ValueObjects\Rank;
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
        $employee = new Employee(new Manager(), new Rank(1), true);

        $this->assertTrue($employee->isLeader());

        $employee->removeLeadership();

        $this->assertFalse($employee->isLeader());
    }

    public function testRemoveLeadershipReturnEmployee()
    {
        $employee = new Employee(new Manager(), new Rank(1));

        $this->assertInstanceOf(Employee::class, $employee->removeLeadership());
    }

    public function testDefineAsLeader()
    {
        $employee = new Employee(new Manager(), new Rank(1));

        $this->assertFalse($employee->isLeader());

        $employee->defineAsLeader();

        $this->assertTrue($employee->isLeader());
    }

    public function testGetRateIfRankIsIncorrect()
    {
        $this->expectException(InvalidRankException::class);

        $employee = new Employee(new Manager(), new Rank(0));
    }

    public function testSetJob()
    {
        $employee = new Employee(new Manager(), new Rank(2));

        $employee->setJob(new Analyst(), new Rank(3), true);
        $this->assertEquals('App\Jobs\Analyst', get_class($employee->getJob()));
        $this->assertEquals(3, $employee->getRank()->getValue());
        $this->assertEquals(true, $employee->isLeader());

        $employee->setJob(new Marketer(), new Rank(1));
        $this->assertEquals('App\Jobs\Marketer', get_class($employee->getJob()));
        $this->assertEquals(1, $employee->getRank()->getValue());
        $this->assertEquals(false, $employee->isLeader());
    }

    public function employeeRateTestProvider(): array
    {
        return [
            [new Employee(new Manager(), new Rank(1)), 500],
            [new Employee(new Manager(), new Rank(2)), 625],
            [new Employee(new Manager(), new Rank(3)), 750],
            [new Employee(new Manager(), new Rank(1), true), 750],
            [new Employee(new Manager(), new Rank(3), true), 1125],
        ];
    }

    public function employeeCoffeeTestProvider(): array
    {
        return [
            [new Employee(new Manager(), new Rank(1)), 20],
            [new Employee(new Manager(), new Rank(2)), 20],
            [new Employee(new Manager(), new Rank(3)), 20],
            [new Employee(new Manager(), new Rank(1), true), 40],
            [new Employee(new Manager(), new Rank(3), true), 40],
        ];
    }

    public function employeeReportsTestProvider(): array
    {
        return [
            [new Employee(new Manager(), new Rank(1)), 200],
            [new Employee(new Manager(), new Rank(2)), 200],
            [new Employee(new Manager(), new Rank(3)), 200],
            [new Employee(new Manager(), new Rank(1), true), 0],
            [new Employee(new Manager(), new Rank(3), true), 0],
        ];
    }
}