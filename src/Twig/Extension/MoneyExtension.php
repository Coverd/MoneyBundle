<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Twig\Extension;

use Coverd\MoneyBundle\Formatter\MoneyFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MoneyExtension extends AbstractExtension
{
    private $moneyFormatter;

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
            new TwigFilter('money_localized_format', [$this->moneyFormatter, 'localizedFormatMoney']),
            new TwigFilter('money_format', [$this->moneyFormatter, 'formatMoney']),
            new TwigFilter('money_format_amount', [$this->moneyFormatter, 'formatAmount']),
            new TwigFilter('money_as_float', [$this->moneyFormatter, 'asFloat']),
            new TwigFilter('currency_symbol', [$this->moneyFormatter, 'formatCurrencyAsSymbol']),
        ];
    }
}
