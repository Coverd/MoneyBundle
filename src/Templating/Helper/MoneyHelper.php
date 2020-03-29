<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Templating\Helper;

use Coverd\MoneyBundle\Formatter\MoneyFormatter;
use Money\Currency;
use Money\Money;

class MoneyHelper
{
    private $moneyFormatter;

    public function __construct(MoneyFormatter $moneyFormatter)
    {
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * Formats the given Money object
     * INCLUDING the currency symbol.
     */
    public function format(Money $money, string $decPoint = ',', string $thousandsSep = ' '): string
    {
        return $this->moneyFormatter->formatMoney($money, $decPoint, $thousandsSep);
    }

    /**
     * Formats the amount part of the given Money object
     * WITHOUT INCLUDING the currency symbol.
     */
    public function formatAmount(Money $money, string $decPoint = ',', string $thousandsSep = ' '): string
    {
        return $this->moneyFormatter->formatAmount($money, $decPoint, $thousandsSep);
    }

    /**
     * Returns the amount for the given Money object as simple float.
     */
    public function asFloat(Money $money): float
    {
        return $this->moneyFormatter->asFloat($money);
    }

    /**
     * Returns the symbol corresponding to the given currency.
     */
    public function symbol(Currency $currency): string
    {
        return $this->moneyFormatter->formatCurrencyAsSymbol($currency);
    }
}
