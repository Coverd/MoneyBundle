<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Templating\Helper;

use Coverd\MoneyBundle\Formatter\MoneyFormatter;
use Money\Currency;

/**
 * Class CurrencyHelper.
 */
class CurrencyHelper
{
    /**
     * @var MoneyFormatter
     */
    protected $moneyFormatter;

    /**
     * Constructor.
     */
    public function __construct(MoneyFormatter $moneyFormatter)
    {
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * Returns the name as string of the given currency.
     *
     * @return string
     */
    public function name(Currency $currency)
    {
        return $this->moneyFormatter->formatCurrencyAsName($currency);
    }

    /**
     * Returns the symbol corresponding to the given currency.
     *
     * @return string
     */
    public function symbol(Currency $currency)
    {
        return $this->moneyFormatter->formatCurrencyAsSymbol($currency);
    }
}
