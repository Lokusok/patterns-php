<?php

class Message
{
    //
}

class Prototype
{
    private string $primitive;

    public Message $message;

    public function __construct()
    {
        $this->message = new Message;
    }

    /**
     * В PHP присутствует встроенная поддержка клонирования.
     * От нас лишь требуется, если необходимо, совершить клонирование
     * объектов, которые лежат в свойствах клонируемого объекта
     */
    public function __clone()
    {
        $this->message = clone $this->message;
    }
}

$p1 = new Prototype;
$p2 = clone $p1;

// Т.к. совершаем глубокое клонирование на 26 строке, то ожидаемо увидим false
var_dump($p1->message === $p2->message);