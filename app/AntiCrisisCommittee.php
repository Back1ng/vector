<?php declare(strict_types=1);


namespace App;


use App\Jobs\Analyst;
use App\Jobs\Engineer;
use App\Jobs\Manager;
use App\ValueObjects\Rank;

class AntiCrisisCommittee
{
    private Company $company;
    private const PERCENT_DISMISS_EMPLOYEE = 0.4;
    private const PERCENT_RANK_UP_EMPLOYEE_OF_FIRST_RANK = 0.5;
    private const PERCENT_RANK_UP_EMPLOYEE_OF_SECOND_RANK = 0.5;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Получить компанию
     *
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setAntiCrisisMeasuresFirst(): bool
    {
        $iterations = 0;

        foreach ($this->company->getDepartments() as $department) {
            $engineers = $department->getEmployeesByJob(new Engineer());

            $engineers = $this->getSortedEmployees($engineers);

            $dismiss = array_slice($engineers, 0, intval(count($engineers) * self::PERCENT_DISMISS_EMPLOYEE));

            array_walk($dismiss, fn(Employee $employee) => $department->dismissEmployee($employee));

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

            if (!$this->isAnalyst($leader)) {
                usort($analytics, fn($a, $b) => $a->getRank() <=> $b->getRank());

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
            $employeesFirstRank = $department->getEmployeesByJobAndRank(new Manager(), new Rank(1));
            $employeesSecondRank = $department->getEmployeesByJobAndRank(new Manager(), new Rank(2));

            for ($i = 0; $i < intval(ceil(count($employeesSecondRank) * self::PERCENT_RANK_UP_EMPLOYEE_OF_FIRST_RANK)); $i++) {
                if ($employeesSecondRank[$i]->getRank() == new Rank(2)) {
                    $employeesSecondRank[$i]->setRank(new Rank(3));
                }
            }

            for ($i = 0; $i < intval(ceil(count($employeesFirstRank) * self::PERCENT_RANK_UP_EMPLOYEE_OF_SECOND_RANK)); $i++) {
                if ($employeesFirstRank[$i]->getRank() == new Rank(1)) {
                    $employeesFirstRank[$i]->setRank(new Rank(2));
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

    private function isAnalyst(Employee $employee): bool
    {
        return $employee->getJob() instanceof Analyst;
    }
}