<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Twig\Extension;

use Coverd\MoneyBundle\Formatter\MoneyFormatter;
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
            new TwigFilter('money_localized_format', [$this->moneyFormatter, 'localizedFormatMoney']),
            new TwigFilter('money_format', [$this->moneyFormatter, 'formatMoney']),
            new TwigFilter('money_format_amount', [$this->moneyFormatter, 'formatAmount']),
            new TwigFilter('money_format_currency', [$this->moneyFormatter, 'formatCurrency']),
            new TwigFilter('money_as_float', [$this->moneyFormatter, 'asFloat']),
            new TwigFilter('money_get_currency', [$this->moneyFormatter, 'getCurrency']),
        ];
    }
}
