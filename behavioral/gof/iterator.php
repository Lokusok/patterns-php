<?php

/**
 * Итератор - предоставляет способ последовательного обращения ко всем элементам
 * составного объекта без раскрытия его внутреннего представления.
 * 
 * Большинство коллекций выглядят как обычный список элементов. Но есть коллекции,
 * построенные на основе деревьев, графов и других сложных структур данных.
 * 
 * Но как бы ни была структурирована коллекция, пользователь
 * должен иметь возможность последовательно обходить её элементы,
 * чтобы проделывать с ними какие-то действия.
 * 
 * Идея паттерна Итератор состоит в том,
 * чтобы вынести поведение обхода коллекции из самой коллекции в отдельный класс.
 */

/**
 * За обращения к элементам и способ обхода отвечает не сам список,
 * а отдельный объект-итератор.
 * 
 * Отделение стратегии механизма обхода (классов итераторов: AlphabeticalOrderIterator, FilterableOrderIterator)
 * от конкретной коллекции (AuthorsCollection),
 * позволяет определять множество различных итераторов,
 * не перечисляя их в интерфейсе класса конкретной коллекции
 */
class AlphabeticalOrderIterator implements \Iterator
{
    private array $collection;
    private int $offset;

    public function __construct($collection)
    {
        sort($collection);
        $this->collection = $collection;
    }

    public function current(): mixed
    {
        return $this->collection[$this->offset];
    }

    public function key(): mixed
    {
        return $this->offset;
    }

    public function next(): void
    {
        $this->offset++;
    }

    public function rewind(): void
    {
        $this->offset = 0;
    }

    public function valid(): bool
    {
        return $this->offset < count($this->collection);
    }
}

class FilterableOrderIterator implements \Iterator
{
    private array $collection;
    private int $offset;

    public function __construct($collection, string $except)
    {
        sort($collection);
        array_splice($collection, array_search($except, $collection), 1);

        $this->collection = $collection;
    }

    public function current(): mixed
    {
        return $this->collection[$this->offset];
    }

    public function key(): mixed
    {
        return $this->offset;
    }

    public function next(): void
    {
        $this->offset++;
    }

    public function rewind(): void
    {
        $this->offset = 0;
    }

    public function valid(): bool
    {
        return $this->offset < count($this->collection);
    }
}

class AuthorsCollection implements \IteratorAggregate
{
    private array $collection = [];

    public function addItem(string $author)
    {
        array_push($this->collection, $author);
    }

    /**
     * Ответственность за создание подходящих объектов-списков будет возложена
     * на сами объекты списки.
     */
    public function getIterator(): Traversable
    {
        return new AlphabeticalOrderIterator($this->collection);
    }

    public function getFilterIterator(string $except): Traversable
    {
        return new FilterableOrderIterator($this->collection, $except);
    }
}

$authorsCollection = new AuthorsCollection();

$authorsCollection->addItem('Marcus Aurelius');
$authorsCollection->addItem('Lev Tolstoy');
$authorsCollection->addItem('Dante Aligheri');

foreach ($authorsCollection as $author) {
    echo $author . PHP_EOL;
}

echo  "======" . PHP_EOL;

foreach ($authorsCollection->getFilterIterator('Lev Tolstoy') as $author) {
    echo $author . PHP_EOL;
}
