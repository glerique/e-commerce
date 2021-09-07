<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('amount', [$this, 'amount'])
        ];
    }

    public function amount($value, string $decsep = ',', string $thousandsep = ' ', string $currency = '€')
    {
        $finalValue = $value / 100;

        $finalValue = number_format($finalValue, 2, $decsep, $thousandsep);

        return $finalValue . $currency;
    }
}
