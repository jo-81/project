<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class ParagraphDataTransformer implements DataTransformerInterface
{
    public function transform($value): ?string
    {
        return $value;
    }

    public function reverseTransform($value): ?string
    {
        if ($value) {
            $paragraphs = explode("\n", $value);
            $value = '<p>'.implode('</p><p>', $paragraphs).'</p>';
        }

        return $value;
    }
}
