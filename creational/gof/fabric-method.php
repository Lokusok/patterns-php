<?php

interface ButtonInterface
{
    public function render(): void;

    public function onClick(): void;
}

class WindowsButton implements ButtonInterface
{
    public function render(): void
    {
        echo '<windows-button />' ;
    }

    public function onClick(): void
    {
        echo 'Clicked on WindowsButton';
    }
}

class LinuxButton implements ButtonInterface
{
    public function render(): void
    {
        echo '<linux-button />' ;
    }

    public function onClick(): void
    {
        echo 'Clicked on LinuxButton';
    } 
}

abstract class AbstractGuiFactory
{
    abstract public function makeButton(): ButtonInterface;
}

class WindowsGuiFactory extends AbstractGuiFactory
{
    public function makeButton(): ButtonInterface
    {
        return new WindowsButton;
    }
}

class LinuxGuiFactory extends AbstractGuiFactory
{
    public function makeButton(): ButtonInterface
    {
        return new LinuxButton;
    }
}

$platforms = ['windows', 'linux'];
$currentPlatform = $platforms[array_rand($platforms)];

// Выбираем нужную фабрику исходя из платформы
if ($currentPlatform === 'windows') {
    $guiFactory = new WindowsGuiFactory;
} elseif ($currentPlatform === 'linux') {
    $guiFactory = new LinuxGuiFactory;
}

// Получаем определённую фабрику с общим интерфейсом.
// Исходя из принципа зависимости от абстракции (в нашем случае интерфейса)
// Программе далее должно быть всё равно, фабрика эта WindowsGuiFactory или LinuxGuiFactory
// (Т.к. везде тайпхинтим через интерфейс)$button = $guiFactory->makeButton();
$button = $guiFactory->makeButton();

var_dump($button);