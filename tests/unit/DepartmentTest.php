<?php declare(strict_types=1);

namespace unit;

use App\Department;
use App\Employee;
use App\Exceptions\LeaderNotFoundException;
use App\Jobs\Analyst;
use App\Jobs\Engineer;
use App\Jobs\Manager;
use App\Jobs\Marketer;
use PHPUnit\Framework\TestCase;

class DepartmentTest extends TestCase
{
    private Department $department;

    protected function setUp(): void
    {
        $this->department = new Department('Департамент');
    }

    public function testAddOneEmployee()
    {
        $employee = new Employee(new Analyst(), 2);

        $this->department->addEmployee($employee);

        $this->assertEquals(1, $this->department->getCountEmployee());
    }

    public function testAddTwoEmployeesByArgument()
    {
        $employee = new Employee(new Analyst(), 2);

        $this->department->addEmployee($employee, 2);

        $this->assertEquals(2, $this->department->getCountEmployee());
    }

    public function testAddTwoEmployeesByDoubleCalls()
    {
        $firstEmployee = new Employee(new Manager(), 1);
        $secondEmployee = new Employee(new Analyst(), 3);

        $this->department->addEmployee($firstEmployee);
        $this->department->addEmployee($secondEmployee);

        $this->assertEquals(2, $this->department->getCountEmployee());

        $this->assertEquals(1, $this->department->getEmployees()[0]->getRank());
        $this->assertEquals(3, $this->department->getEmployees()[1]->getRank());
    }

    public function testAddTwoEmployeesByDoubleSelfCalls()
    {
        $firstEmployee = new Employee(new Manager(), 1);
        $secondEmployee = new Employee(new Analyst(), 3);

        $this->department
            ->addEmployee($firstEmployee)
            ->addEmployee($secondEmployee);

        $this->assertEquals(2, $this->department->getCountEmployee());

        $this->assertEquals(1, $this->department->getEmployees()[0]->getRank());
        $this->assertEquals(3, $this->department->getEmployees()[1]->getRank());
    }

    /**
     * @depends testAddTwoEmployeesByArgument
     */
    public function testThatAddEmployeeAddsNotLinkedEmployees()
    {
        $employee = new Employee(new Analyst(), 2);

        $this->department->addEmployee($employee, 2);

        $this->department->getEmployees()[0]->setRank(1);
        $this->department->getEmployees()[0]->getJob()->setReport(100);

        $this->assertEquals(1, $this->department->getEmployees()[0]->getRank());
        $this->assertEquals(100, $this->department->getEmployees()[0]->getReport());

        $this->assertEquals(2, $this->department->getEmployees()[1]->getRank());
        $this->assertEquals(5, $this->department->getEmployees()[1]->getReport());

    }

    public function testGetEmployeesWhenDepartmentHaveNotEmployees()
    {
        $this->assertEquals([], $this->department->getEmployees());
    }

    public function testGetEmployees()
    {
        $this->department->addEmployee(new Employee(new Manager(), 1), 10);

        $this->assertEquals(1, $this->department->getEmployees()[3]->getRank());
    }

    public function testGetCountEmployeesWhenDepartmentHaveNotEmployees()
    {
        $this->assertEquals(0, $this->department->getCountEmployee());
    }

    public function testGetCountEmployees()
    {
        $this->department->addEmployee(new Employee(new Manager(), 1), 10);

        $this->assertEquals(10, $this->department->getCountEmployee());
    }

    public function testGetMoneyExpensesReturnZeroWhenDepartmentHaveNotEmployees()
    {
        $this->assertEquals(0, $this->department->getMoneyExpenses());
    }

    public function testGetMoneyExpenses()
    {
        $this->addEmployees();
        $this->assertEquals(6250, $this->department->getMoneyExpenses());

        $this->setUp();

        $this->addEmployees(true);
        $this->assertEquals(11100, $this->department->getMoneyExpenses());
    }

    public function testGetCoffeeExpensesReturnZeroWhenDepartmentHaveNotEmployees()
    {
        $this->assertEquals(0, $this->department->getCoffeeExpenses());
    }

    public function testGetCoffeeExpenses()
    {
        $this->addEmployees();
        $this->assertEquals(200, $this->department->getCoffeeExpenses());

        $this->setUp();

        $this->addEmployees(true);
        $this->assertEquals(360, $this->department->getCoffeeExpenses());
    }

    public function testGetReportsReturnZeroWhenDepartmentHaveNotEmployees()
    {
        $this->assertEquals(0, $this->department->getReports());
    }

    public function testGetReports()
    {
        $this->addEmployees();
        $this->assertEquals(2000, $this->department->getReports());

        $this->setUp();

        $this->addEmployees(true);
        $this->assertEquals(300, $this->department->getReports());
    }

    public function testThatGetAverageConsumptionMoneyPerPageReturnZeroWhenDepartmentHaveNotEmployees()
    {
        $this->assertEquals(0, $this->department->getAverageConsumptionMoneyPerPage());
    }

    public function testGetAverageConsumptionMoneyPerPage()
    {
        $this->addEmployees();

        $this->assertEquals(3.13, $this->department->getAverageConsumptionMoneyPerPage());
    }

    public function testDismissEmployeeCanDismissEmployee()
    {
        $employee = new Employee(new Manager(), 2);

        $this->department->addEmployee($employee);

        $this->assertEquals(1, $this->department->getCountEmployee());

        $this->department->dismissEmployee($employee);

        $this->assertEquals(0, $this->department->getCountEmployee());
    }

    public function testDismissEmployeeRemoveOneOfTwoEmployees()
    {
        $firstEmployee = new Employee(new Manager(), 2);
        $secondEmployee = new Employee(new Manager(), 3);

        $this->department->addEmployee($firstEmployee);
        $this->department->addEmployee($secondEmployee);

        $this->assertEquals(2, $this->department->getCountEmployee());

        $this->department->dismissEmployee($firstEmployee);

        $this->assertEquals(1, $this->department->getCountEmployee());
        $this->assertEquals(3, $this->department->getEmployees()[0]->getRank());
    }

    public function testGetEmployeesByJobWithOneEmployee()
    {
        $employee = new Employee(new Manager(), 2);

        $this->department->addEmployee($employee);

        $this->assertEquals(1, $this->department->getCountEmployee());
        $this->assertEquals(1, count($this->department->getEmployeesByJob(new Manager())));
        $this->assertEquals(0, count($this->department->getEmployeesByJob(new Analyst())));
    }

    public function testGetEmployeesByJobWithTwoEmployee()
    {
        $employee = new Employee(new Manager(), 2);

        $this->department->addEmployee($employee, 2);

        $this->assertEquals(2, count($this->department->getEmployeesByJob(new Manager())));
    }

    public function testGetLeaderThrowAnException()
    {
        $this->expectException(LeaderNotFoundException::class);

        $this->department->getLeader();
    }

    public function testGetLeaderReturnLeader()
    {
        $employee = new Employee(new Manager(), 2, true);

        $this->department->addEmployee($employee);

        $this->assertTrue($this->department->getLeader()->isLeader());
    }

    public function testGetEmployeesByJobAndRank()
    {
        $this->department->addEmployee(new Employee(new Marketer(), 2), 5);
        $this->department->addEmployee(new Employee(new Marketer(), 1), 5);

        $this->assertEquals(5, count($this->department->getEmployeesByJobAndRank(new Marketer(), 2)));
    }

    public function testGetLeaders()
    {
        $this->addEmployees(true);

        $this->assertEquals(11, count($this->department->getLeaders()));
    }

    private function addEmployees(bool $withLeadership = false)
    {
        if ($withLeadership) {
            $this->department->addEmployee(new Employee(new Marketer(), 3, true), 11);
            $this->department->addEmployee(new Employee(new Marketer(), 3), 2);
        } else {
            $this->department->addEmployee(new Employee(new Manager(), 2), 10);
        }
    }

    public function testConsumptionMoneyPerPage()
    {

        $ad = (new Department('рекламы'))
            ->addEmployee(new Employee(new Marketer(), 1), 15)
            ->addEmployee(new Employee(new Marketer(), 2), 10)
            ->addEmployee(new Employee(new Manager(), 1), 8)
            ->addEmployee(new Employee(new Engineer(), 1), 2)
            ->addEmployee(new Employee(new Marketer(), 3, true));

        $this->assertEquals(1001.35, $ad->getConsumptionMoneyPerPage());
    }
}