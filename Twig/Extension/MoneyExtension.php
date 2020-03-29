<?php

namespace Coverd\MoneyBundle\Twig\Extension;

use Money\Money;
use Coverd\MoneyBundle\Formatter\MoneyFormatter;
use Coverd\MoneyBundle\Pair\PairManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @author Philippe Le Van <philippe.levan@kitpages.fr>
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class MoneyExtension extends AbstractExtension
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
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            new TwigFilter('money_localized_format', array($this->moneyFormatter, 'localizedFormatMoney')),
            new TwigFilter('money_format', array($this->moneyFormatter, 'formatMoney')),
            new TwigFilter('money_format_amount', array($this->moneyFormatter, 'formatAmount')),
            new TwigFilter('money_format_currency', array($this->moneyFormatter, 'formatCurrency')),
            new TwigFilter('money_as_float', array($this->moneyFormatter, 'asFloat')),
            new TwigFilter('money_get_currency', array($this->moneyFormatter, 'getCurrency')),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'coverd_money_extension';
    }
}
