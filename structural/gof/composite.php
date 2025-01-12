<?php

/**
 * Компоновщик — это структурный паттерн проектирования,
 * который позволяет сгруппировать множество объектов в древовидную структуру,
 * а затем работать с ней так, как будто это единичный объект.
 * 
 * Паттерн Компоновщик имеет смысл только тогда,
 * когда основная модель вашей программы может быть структурирована в виде дерева.
 * 
 * Паттерн Компоновщик описывает, как можно применить рекурсивную
 * композицию таким образом, что клиенту не придётся проводить различие
 * между простыми и составными объектами.
 */

/**
 * Ключом к паттерну Компоновщик является интерфейс, который представляет
 * одновременно и примитивы и контейнеры. (По GOF правильнее использовать абстрактный класс)
 *
 * У единичной сущности и контейнера должен быть единый интерфейс.
 */
interface Member
{
    public function getSalary(): float|int;
}

class Worker implements Member
{
    private float|int $salary;

    public function __construct(float|int $salary)
    {
        $this->salary = $salary;
    }

    public function getSalary(): float|int
    {
        return $this->salary;
    }
}

class CompoundWorker implements Member
{
    /**
     * @var Member[] $members
     */
    private array $members = [];

    public function add(Member ...$members)
    {
        array_push($this->members, ...$members);
    }

    /**
     * Данный метод контейнера переадресует основную работу своим дочерним компонентам.
     */
    public function getSalary(): float|int
    {
        $result = 0;

        foreach ($this->members as $member) {
            $result += $member->getSalary();
        }

        return $result;
    }
}

/**
 * Образуем древовидную структуру
 */
$compoundWorker1 = new CompoundWorker;

$worker1 = new Worker(100);
$worker2 = new Worker(100);
$worker3 = new Worker(100);

$compoundWorker1->add($worker1, $worker2, $worker3);

$compoundWorker2 = new CompoundWorker;

$worker4 = new Worker(500);
$worker5 = new Worker(500);
$worker6 = new Worker(500);

$compoundWorker2->add($compoundWorker1, $worker4, $worker5, $worker6);

/**
 * Считаем итоговую зарплату.
 * Клиентскому коду всё равно на древовидность.
 * 
 * Главное для клиентского кода состоит в том,что
 * теперь не нужно ничего знать о структуре работников.
 * Мы вызываем метод итоговой зарплаты, и получаем простое число,
 * мы не уходим в дебри рекурсии из клиентского кода,
 * а имеем удобный интерфейс взаимодействия.
 */
$result = $compoundWorker2->getSalary();
echo "Result = {$result}" . PHP_EOL;