<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Templating\Helper;

use Coverd\MoneyBundle\Formatter\MoneyFormatter;
use Money\Money;

/**
 * Class MoneyHelper.
 */
class MoneyHelper
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
     * Formats the given Money object
     * INCLUDING the currency symbol.
     *
     * @param string $decPoint
     * @param string $thousandsSep
     *
     * @return string
     */
    public function format(Money $money, $decPoint = ',', $thousandsSep = ' ')
    {
        return $this->moneyFormatter->formatMoney($money, $decPoint, $thousandsSep);
    }

    /**
     * Formats the amount part of the given Money object
     * WITHOUT INCLUDING the currency symbol.
     *
     * @param string $decPoint
     * @param string $thousandsSep
     *
     * @return string
     */
    public function formatAmount(Money $money, $decPoint = ',', $thousandsSep = ' ')
    {
        return $this->moneyFormatter->formatAmount($money, $decPoint, $thousandsSep);
    }

    /**
     * Returns the amount for the given Money object as simple float.
     *
     * @return float
     */
    public function asFloat(Money $money)
    {
        return $this->moneyFormatter->asFloat($money);
    }

    /**
     * Formats only the currency part of the given Money object.
     *
     * @param Money $money
     *
     * @return string
     */
    public function formatCurrency($money)
    {
        return $this->moneyFormatter->formatCurrency($money);
    }
}
