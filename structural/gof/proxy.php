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
 * Прокси. Содержит в себе ссылку, позволяющую обратиться к реальному субъекту.
 * Прокси может сам отвечать за создание и удаление объекта сервиса.
 */
class ProductManager implements MakerInterface
{
    private Developer $developer;

    public function __construct()
    {
        /**
         * Чаще всего, сервисный объект создаётся самим прокси.
         * В редких случаях заместитель получает готовый сервисный 
         * объект от клиента (от клиентского кода) через конструктор
         */
        $this->developer = new Developer;
    }

    public function addTask(string $task): void
    {
        $this->developer->addTask($task);
    }

    public function completeAllTasks(): void
    {
        /**
         * Проверка прав для выполнения нужной операции.
         * Согласно GOF, этот прокси является "Защищающим"
         */
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

$productManager = new ProductManager();

$productManager->addTask('dev#1 make header');
$productManager->addTask('dev#2 add body');
$productManager->addTask('dev#3 add footer');

$productManager->completeAllTasks();