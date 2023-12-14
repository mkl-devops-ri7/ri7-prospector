<?php

namespace App\Twig\Components\Ui;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Button
{
    public string $type = 'button';
    public string $color = 'teal-600';
    public string $hover = 'teal-500';
    public string $id = '';
    public string $class = '';

    public function getClass(): string
    {
        $class = strtr('border-%color% bg-%color% hover:bg-%hover% hover:border-%hover% active:text-%hover%', ['%color%' => $this->color, '%hover%' => $this->hover]);
        $class .= ' inline-block rounded border px-12 py-3 text-sm font-medium text-white focus:outline-none focus:ring';
        $class .= ' '.$this->class;

        return implode(' ', array_unique(explode(' ', $class)));
    }
}
