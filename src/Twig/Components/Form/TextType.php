<?php

namespace App\Twig\Components\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class TextType
{
    public string $type = 'text';

    public string $label = '';

    public string $name = '';

    public mixed $value = '';

    public array $options = [];

    public function listAttributes(): string
    {
        $attribute = '';
        foreach ($this->options as $key => $value) {
            if (is_bool($value) && $value) {
                $attribute .= "$key ";
            } else {
                $attribute .= "$key=$value ";
            }
        }

        return trim($attribute);
    }
}
