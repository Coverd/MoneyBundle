<?php

declare(strict_types=1);
/**
 * Created by Philippe Le Van.
 * Date: 03/07/13.
 */

namespace Coverd\MoneyBundle\Twig\Extension;

use Coverd\MoneyBundle\Formatter\MoneyFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @author Philippe Le Van <philippe.levan@kitpages.fr>
 * @author Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class CurrencyExtension extends AbstractExtension
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
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('currency_name', [$this->moneyFormatter, 'formatCurrencyAsName']),
            new TwigFilter('currency_symbol', [$this->moneyFormatter, 'formatCurrencyAsSymbol']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'coverd_money_currency_extension';
    }
}
