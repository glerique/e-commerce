<?php

namespace App\From\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class CentsTransformer implements DataTransformerInterface
{

    public function transform($value)
    {
        if ($value === null) {
            return;
        }
        return $value / 100;
    }

    public function reverseTransform($value)
    {

        if ($value === null) {
            return;
        }
        return $value * 100;
    }
}
