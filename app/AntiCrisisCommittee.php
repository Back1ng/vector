<?php


namespace App;


use App\Jobs\Analyst;
use App\Jobs\Engineer;
use App\Jobs\Manager;

class AntiCrisisCommittee
{
    private Company $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Получить компанию
     *
     * @return Company
     */
    public function getCompany() : Company
    {
        return $this->company;
    }

    public function setAntiCrisisMeasuresFirst()
    {
        foreach ($this->company->getDepartments() as $department) {
            $engineers = $department->getEmployeesByJob(new Engineer());

            usort($engineers, function($a, $b) {
                if ($a->isLeader() && $b->isLeader()) return 0;
                if ($a->isLeader()) return 1;
                return $a->getRank() <=> $b->getRank();
            });

            $dismiss = array_slice($engineers, 0, ceil(count($engineers) * 0.4));

            array_map(function ($employee) use ($department) {
                $department->dismissEmployee($employee);
            }, $dismiss);
        }
    }

    public function setAntiCrisisMeasuresSecond()
    {
        foreach ($this->company->getDepartments() as $department) {
            $analytics = array_map(function ($employee) {
                $employee->getJob()->setRate(1100)->setCoffee(75);
                return $employee;
            }, $department->getEmployeesByJob(new Analyst()));

            if($analytics === []) continue;

            $leader = $department->getLeader();

            if (!($leader instanceof Analyst)) {
                usort($analytics, function ($a, $b) {
                    return $a->getRank() <=> $b->getRank();
                });
                array_pop($analytics)->inverseLeader();
                $leader->inverseLeader();
            }
        }
    }

    public function setAntiCrisisMeasuresThird()
    {
        foreach ($this->company->getDepartments() as $department) {
            $employeesFirstRank  = $department->getEmployeesByJobAndRank(new Manager(), 1);
            $employeesSecondRank = $department->getEmployeesByJobAndRank(new Manager(), 2);

            array_map(function (Employee $employee) {
                $employee->setRank($employee->getRank() + 1);
                return $employee;
            }, array_merge(
                array_slice($employeesFirstRank,  0, ceil(count($employeesFirstRank) * 0.5)),
                array_slice($employeesSecondRank, 0, ceil(count($employeesSecondRank) * 0.5))
            ));
        }
    }
}