<?php declare(strict_types=1);


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

    public function setAntiCrisisMeasuresFirst(): bool
    {
        $iterations = 0;

        foreach ($this->company->getDepartments() as $department) {
            $engineers = $department->getEmployeesByJob(new Engineer());

            $engineers = $this->getSortedEmployees($engineers);

            $dismiss = array_slice($engineers, 0, intval(count($engineers) * 0.4));

            array_walk($dismiss, function ($employee) use ($department) {
                $department->dismissEmployee($employee);
            });
            $iterations++;
        }

        return $iterations === $this->company->getCountDepartments();
    }

    public function setAntiCrisisMeasuresSecond(): bool
    {
        $iterations = 0;

        foreach ($this->company->getDepartments() as $department) {
            $analytics = $this->getAnalyticsWithChangedExpenses($department);
            $iterations++;
            if ([] === $analytics) {
                continue;
            }
            $leader = $department->getLeader();

            if (! ($leader->getJob() instanceof Analyst)) {
                usort($analytics, function ($a, $b) {
                    return $a->getRank() <=> $b->getRank();
                });
                array_pop($analytics)->defineAsLeader();
                $leader->removeLeadership();
            }
        }

        return $iterations === $this->company->getCountDepartments();
    }

    public function setAntiCrisisMeasuresThird()
    {
        $iteration = 0;

        foreach ($this->company->getDepartments() as $department) {
            $employeesFirstRank  = $department->getEmployeesByJobAndRank(new Manager(), 1);
            $employeesSecondRank = $department->getEmployeesByJobAndRank(new Manager(), 2);

            for ($i = 0; $i < intval(ceil(count($employeesSecondRank) * 0.5)); $i++) {
                if ($employeesSecondRank[$i]->getRank() === 2) {
                    $employeesSecondRank[$i]->setRank(3);
                }
            }

            for ($i = 0; $i < intval(ceil(count($employeesFirstRank) * 0.5)); $i++) {
                if ($employeesFirstRank[$i]->getRank() === 1) {
                    $employeesFirstRank[$i]->setRank(2);
                }
            }

            $iteration++;
        }

        return $iteration === $this->company->getCountDepartments();
    }

    /**
     * @param $engineers
     * @return mixed
     */
    public function getSortedEmployees($engineers)
    {
        usort($engineers, function ($a, $b) {
            if ($a->isLeader() && $b->isLeader()) {
                return 0;
            }
            if ($a->isLeader()) {
                return 1;
            }
            return $a->getRank() <=> $b->getRank();
        });

        return $engineers;
    }

    /**
     * @param mixed $department
     * @return array
     */
    public function getAnalyticsWithChangedExpenses(Department $department): array
    {
        return array_map(function ($employee) use ($department) {
            $employee->getJob()->setRate(1100)->setCoffee(75);
            return $employee;
        }, $department->getEmployeesByJob(new Analyst()));
    }
}