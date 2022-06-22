<?php declare(strict_types=1);

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

require "../vendor/autoload.php";

function padRight($string, int $length): string
{
    $times = $length - mb_strlen(strval($string));
    return $string . str_repeat(' ', $times >= 0 ? $times : 0);
}

function padLeft($string, int $length): string
{
    $times = $length - mb_strlen(strval($string));
    return str_repeat(' ', $times >= 0 ? $times : 0) . $string;
}

function write($vector) {
    foreach ($vector->getDepartments() as $department) {
        echo padRight($department->getName(), 11)
            .padLeft($department->getCountEmployee(), 11)
            .padLeft($department->getMoneyExpenses(), 11)
            .padLeft($department->getCoffeeExpenses(), 11)
            .padLeft($department->getReports(), 11)
            .padLeft($department->getAverageConsumptionMoneyPerPage(), 11) . PHP_EOL;
    }
    echo padRight('Среднее', 11)
        .padLeft($vector->getAverageCountEmployees(), 11)
        .padLeft($vector->getAverageMoneyExpenses(), 11)
        .padLeft($vector->getAverageCoffeeExpenses(), 11)
        .padLeft($vector->getAverageReports(), 11)
        .padLeft($vector->getAverageConsumptionMoneyPerPage(), 11) . PHP_EOL;
    echo padRight('Всего', 11)
        .padLeft($vector->getCountEmployee(), 11)
        .padLeft($vector->getMoneyExpenses(), 11)
        .padLeft($vector->getCoffeeExpenses(), 11)
        .padLeft($vector->getReports(), 11)
        .padLeft($vector->getConsumptionMoneyPerPage(), 11) . PHP_EOL;
}

$vector = new Company();

$purchasing = (new Department('закупок'))
    ->addEmployee(new Employee(new Manager(), new SeniorRank()), 9)
    ->addEmployee(new Employee(new Manager(), new MiddleRank()), 3)
    ->addEmployee(new Employee(new Manager(), new SeniorRank()), 2)
    ->addEmployee(new Employee(new Marketer(), new NewbeeRank()), 2)
    ->addEmployee(new Employee(new Manager(), new MiddleRank(), true));

$sells = (new Department('продаж'))
    ->addEmployee(new Employee(new Manager(), new NewbeeRank()), 12)
    ->addEmployee(new Employee(new Marketer(), new NewbeeRank()), 6)
    ->addEmployee(new Employee(new Analyst(), new NewbeeRank()), 3)
    ->addEmployee(new Employee(new Analyst(), new MiddleRank()), 2)
    ->addEmployee(new Employee(new Manager(), new MiddleRank(), true));

$ad = (new Department('рекламы'))
    ->addEmployee(new Employee(new Marketer(), new NewbeeRank()), 15)
    ->addEmployee(new Employee(new Marketer(), new MiddleRank()), 10)
    ->addEmployee(new Employee(new Manager(), new NewbeeRank()), 8)
    ->addEmployee(new Employee(new Engineer(), new NewbeeRank()), 2)
    ->addEmployee(new Employee(new Marketer(), new SeniorRank(), true));

$logistics = (new Department('логистики'))
    ->addEmployee(new Employee(new Manager(), new NewbeeRank()), 13)
    ->addEmployee(new Employee(new Manager(), new MiddleRank()), 5)
    ->addEmployee(new Employee(new Engineer(), new NewbeeRank()), 5)
    ->addEmployee(new Employee(new Manager(), new NewbeeRank(), true));

$vector->addDepartment($purchasing);
$vector->addDepartment($sells);
$vector->addDepartment($ad);
$vector->addDepartment($logistics);
echo padLeft("Департамент", 11)
    .padLeft("сотр.", 11)
    .padLeft("тугр.", 11)
    .padLeft("кофе", 11)
    .padLeft("стр.", 11)
    .padLeft("тугр./стр.", 15) . PHP_EOL;

echo '---------------------------------------------------------------------' . PHP_EOL;
echo "=== DEFAULT ===" . PHP_EOL;
write($vector);

echo "=== ANTI-CRISIS FIRST ===" . PHP_EOL;
$antiCrisis = new AntiCrisisCommittee(clone $vector);
$antiCrisis->setAntiCrisisMeasuresFirst();
write($antiCrisis->getCompany());

echo "=== ANTI-CRISIS SECOND ===" . PHP_EOL;
$antiCrisis = new AntiCrisisCommittee(clone $vector);
$antiCrisis->setAntiCrisisMeasuresSecond();
write($antiCrisis->getCompany());

echo "=== ANTI-CRISIS THIRD ===" . PHP_EOL;
$antiCrisis = new AntiCrisisCommittee(clone $vector);
$antiCrisis->setAntiCrisisMeasuresThird();
write($antiCrisis->getCompany());
