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

class GuiFactory
{
    private string $platform;

    public function __construct(string $platform)
    {
        $this->platform = strtolower($platform);
    }

    public function makeButton(): ButtonInterface
    {
        switch ($this->platform) {
            case 'windows':
                return new WindowsButton;
            case 'linux':
                return new LinuxButton;
        }
    }
}

// Симулируем запуск на разных платформах
$platforms = ['windows', 'linux'];
$currentPlatform = $platforms[array_rand($platforms)];

$guiFactory = new GuiFactory($currentPlatform);

// Получаем определённую кнопку с общим интерфейсом.
// Исходя из принципа зависимости от абстракции (в нашем случае интерфейса)
// Программе далее должно быть всё равно, кнопка эта WindowsButton или LinuxButton
// (Т.к. везде тайпхинтим через интерфейс)
$button = $guiFactory->makeButton();

var_dump($button);