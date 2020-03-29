<?php
namespace Coverd\MoneyBundle\Templating\Helper;

use Money\Money;
use Symfony\Component\Templating\Helper\Helper;
use Coverd\MoneyBundle\Formatter\MoneyFormatter;
use Coverd\MoneyBundle\Pair\PairManagerInterface;

/**
 * Class MoneyHelper
 * @package Coverd\MoneyBundle\Templating\Helper
 */
class MoneyHelper extends Helper
{
    /**
     * @var MoneyFormatter
     */
    protected $moneyFormatter;

    /**
     * Constructor
     *
     * @param MoneyFormatter       $moneyFormatter
     */
    public function __construct(MoneyFormatter $moneyFormatter)
    {
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * Formats the given Money object
     * INCLUDING the currency symbol
     *
     * @param Money  $money
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
     * WITHOUT INCLUDING the currency symbol
     *
     * @param Money  $money
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
     * Returns the amount for the given Money object as simple float
     *
     * @param Money $money
     * @return float
     */
    public function asFloat(Money $money)
    {
        return $this->moneyFormatter->asFloat($money);
    }

    /**
     * Formats only the currency part of the given Money object
     *
     * @param Money $money
     * @return string
     */
    public function formatCurrency($money)
    {
        return $this->moneyFormatter->formatCurrency($money);
    }

    /**
     * Returns the Currency object
     *
     * @param Money $money
     * @return \Money\Currency
     */
    public function getCurrency(Money $money)
    {
        return $this->moneyFormatter->getCurrency($money);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'coverd_money_helper';
    }
}
