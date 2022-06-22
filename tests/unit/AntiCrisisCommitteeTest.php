<?php

namespace unit;

use App\AntiCrisisCommittee;
use App\Company;
use App\Department;
use App\Employee;
use App\Jobs\Analyst;
use App\Jobs\Engineer;
use App\Jobs\Manager;
use App\Jobs\Marketer;
use App\ValueObjects\Ranks\MiddleRank;
use App\ValueObjects\Ranks\NewbeeRank;
use App\ValueObjects\Ranks\SeniorRank;
use PHPUnit\Framework\TestCase;

class AntiCrisisCommitteeTest extends TestCase
{

    public AntiCrisisCommittee $comitte;

    public function setUp(): void
    {
        $this->comitte = new AntiCrisisCommittee(new Company());
    }

    public function testGetCompanyIsPublic()
    {
        $this->assertInstanceOf(Company::class, $this->comitte->getCompany());
    }

    public function testSetAntiCrisisMeasuresFirstIsPublic()
    {
        $this->assertIsBool($this->comitte->setAntiCrisisMeasuresFirst());
    }

    public function testSetAntiCrisisMeasuresFirstIsPublicForeachNotEmptyArray()
    {
        $marketing = new Department('маркетинг');
        $company = (new Company())->addDepartment($marketing);
        $comitte = new AntiCrisisCommittee($company);

        $this->assertTrue($comitte->setAntiCrisisMeasuresFirst());
    }

    public function testGetSortedEmployees()
    {
        $marketing = new Department('маркетинг');

        $marketing->addEmployee(new Employee(new Engineer(), new MiddleRank(), true), 2);
        $marketing->addEmployee(new Employee(new Engineer(), new MiddleRank()), 2);

        $company = (new Company())->addDepartment($marketing);
        $comitte = new AntiCrisisCommittee($company);

        $employees = $comitte->getSortedEmployees($marketing->getEmployeesByJob(new Engineer()));

        $this->assertTrue($marketing->getEmployeesByJob(new Engineer())[0]->isLeader());
        $this->assertTrue($employees[3]->isLeader());
    }

    public function testSetAntiCrisisMeasuresFirstReturnCorrectCountEmployees()
    {
        $marketing = new Department('маркетинг');

        $marketing->addEmployee(new Employee(new Engineer(), new NewbeeRank()));
        $marketing->addEmployee(new Employee(new Engineer(), new MiddleRank()), 33);
        $marketing->addEmployee(new Employee(new Engineer(), new SeniorRank(), true));

        $company = (new Company())->addDepartment($marketing);
        $comitte = new AntiCrisisCommittee($company);

        $this->assertTrue($comitte->setAntiCrisisMeasuresFirst());
        $this->assertEquals(21, $comitte->getCompany()->getDepartments()[0]->getCountEmployee());
    }

    public function testSetAntiCrisisMeasuresSecondIsPublicForeachNotEmptyArray()
    {
        $marketing = new Department('маркетинг');
        $another = new Department('test');
        $company = (new Company())->addDepartment($marketing)->addDepartment($another);
        $comitte = new AntiCrisisCommittee($company);

        $this->assertTrue($comitte->setAntiCrisisMeasuresSecond());
    }

    public function testGetAnalyticsWithChangedExpenses()
    {
        $marketing = new Department('маркетинг');

        $marketing->addEmployee(new Employee(new Analyst(), new MiddleRank()));

        $company = (new Company())->addDepartment($marketing);
        $comitte = new AntiCrisisCommittee($company);

        $analyst = $comitte->getAnalyticsWithChangedExpenses($marketing);
        $this->assertEquals(1100, $analyst[0]->getJob()->getRate());
        $this->assertEquals(75, $analyst[0]->getJob()->getCoffee());
    }

    public function testSetAnticrisisMeasuresSecondWithLeaderMarketer()
    {
        $marketing = new Department('маркетинг');

        $marketing->addEmployee(new Employee(new Analyst(), new SeniorRank()));
        $marketing->addEmployee(new Employee(new Analyst(), new MiddleRank()), 10);
        $marketing->addEmployee(new Employee(new Marketer(), new MiddleRank(), true));

        $company = (new Company())->addDepartment($marketing);
        $comitte = new AntiCrisisCommittee($company);

        $this->assertTrue($comitte->setAntiCrisisMeasuresSecond());

        $department = $comitte->getCompany()->getDepartments()[0];
        $this->assertTrue($department->getEmployeesByJobAndRank(new Analyst(), new SeniorRank())[0]->isLeader());
        $this->assertFalse($department->getEmployeesByJob(new Marketer())[0]->isLeader());
    }

    public function testSetAnticrisisMeasuresSecondWithLeaderAnalyst()
    {
        $marketing = new Department('маркетинг');

        $marketing->addEmployee(new Employee(new Analyst(), new SeniorRank(), true));
        $marketing->addEmployee(new Employee(new Analyst(), new MiddleRank()), 10);
        $marketing->addEmployee(new Employee(new Marketer(), new MiddleRank()));

        $company = (new Company())->addDepartment($marketing);
        $comitte = new AntiCrisisCommittee($company);

        $this->assertTrue($comitte->setAntiCrisisMeasuresSecond());

        $department = $comitte->getCompany()->getDepartments()[0];
        $this->assertTrue($department->getEmployeesByJobAndRank(new Analyst(), new SeniorRank())[0]->isLeader());
        $this->assertFalse($department->getEmployeesByJob(new Marketer())[0]->isLeader());
    }

    public function testSetAntiCrisisMeasuresThird()
    {
        $marketing = new Department('маркетинг');

        $marketing->addEmployee(new Employee(new Manager(), new NewbeeRank()), 11);
        $marketing->addEmployee(new Employee(new Manager(), new MiddleRank()), 11);

        $company = (new Company())->addDepartment($marketing);
        $comitte = new AntiCrisisCommittee($company);

        $this->assertTrue($comitte->setAntiCrisisMeasuresThird());

        $this->assertEquals(6, count($company->getDepartments()[0]->getEmployeesByJobAndRank(new Manager(), new SeniorRank())));
        $this->assertEquals(11, count($company->getDepartments()[0]->getEmployeesByJobAndRank(new Manager(), new MiddleRank())));
        $this->assertEquals(5, count($company->getDepartments()[0]->getEmployeesByJobAndRank(new Manager(), new NewbeeRank())));
    }
}
