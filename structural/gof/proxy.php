<?php
/**
 * Прокси - является суррогатом другого объекта и контролирует доступ к нему.
 * Прокси - является прослойкой между клиентом и реальным сервисным объектом.
 * Заместитель получает вызовы от клиента, выполняет свою функцию (контроль доступа,
 * кеширование, изменение запроса и прочее), а затем передаёт вызов сервисному объекту.
 */

/**
 * Прокси и реальный объект следуют одному интерфейсу.
 * Так что заместитель всегда может быть подставлен вместо реального субъекта. 
 */
interface MakerInterface
{
    public function completeAllTasks(): void;
}

/**
 * Обычный объект. На его месте мог быть сервис по взаимодействию с сущностью N.
 */
class Developer implements MakerInterface
{
    private array $tasks = [];

    public function addTask(string $task): void
    {
        array_push($this->tasks, $task);
    }

    public function completeAllTasks(): void
    {
        foreach ($this->tasks as $task) {
            echo "Make: {$task}" . PHP_EOL;
        }
    }
}

/**
 * Прокси. Содержит в себе ссылку, позволяющую обратиться к реальному субъекту
 */
class ProductManager implements MakerInterface
{
    private Developer $developer;

    public function __construct(Developer $developer)
    {
        $this->developer = $developer;
    }

    public function completeAllTasks(): void
    {
        if (! $this->checkAccess()) {
            return;
        }

        $this->developer->completeAllTasks();
    }

    private function checkAccess(): bool
    {
        $isSuccess = random_int(0, 10) > 5;

        if ($isSuccess) {
            echo "You have access to manipulate this product manager" . PHP_EOL;
        } else {
            echo "You have not access to manipulate this product manager" . PHP_EOL;
        }

        return $isSuccess;
    }
}

$developer = new Developer;

$developer->addTask('#1 make header');
$developer->addTask('#2 add body');
$developer->addTask('#3 add footer');

$productManager = new ProductManager($developer);

$productManager->completeAllTasks();