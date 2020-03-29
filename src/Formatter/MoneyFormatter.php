<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Formatter;

use Money\Currency;
use Money\Money;
use Symfony\Component\Intl\Currencies;

/**
 * Money formatter.
 *
 * @author Benjamin Dulau <benjamin@thebigbrainscompany.com>
 */
class MoneyFormatter
{
    private $decimals;

    public function __construct(int $decimals = 2)
    {
        $this->decimals = $decimals;
    }

    /**
     * Format money with the NumberFormatter class.
     *
     * You can force the locale or event use your own $numberFormatter instance to format
     * the output as you wish.
     *
     * @see http://www.php.net/manual/en/numberformatter.formatcurrency.php
     */
    public function localizedFormatMoney(Money $money, ?string $locale = null, \NumberFormatter $numberFormatter = null): string
    {
        if (!($numberFormatter instanceof \NumberFormatter)) {
            $numberFormatter = $this->getDefaultNumberFormatter($money->getCurrency()->getCode(), $locale);
        }

        return $numberFormatter->formatCurrency($this->asFloat($money), $money->getCurrency()->getCode());
    }

    /**
     * Formats the given Money object
     * INCLUDING the currency symbol.
     */
    public function formatMoney(Money $money, string $decPoint = ',', string $thousandsSep = ' '): string
    {
        $symbol = $this->formatCurrencyAsSymbol($money->getCurrency());
        $amount = $this->formatAmount($money, $decPoint, $thousandsSep);
        $price = $amount.' '.$symbol;

        return $price;
    }

    /**
     * Formats the amount part of the given Money object
     * WITHOUT INCLUDING the currency symbol.
     */
    public function formatAmount(Money $money, string $decPoint = ',', string $thousandsSep = ' '): string
    {
        $amount = $this->asFloat($money);
        $amount = number_format($amount, $this->decimals, $decPoint, $thousandsSep);

        return $amount;
    }

    /**
     * Returns the amount for the given Money object as simple float.
     */
    public function asFloat(Money $money): float
    {
        $amount = $money->getAmount();
        $amount = (float) $amount;
        $amount = $amount / pow(10, $this->decimals);

        return $amount;
    }

    /**
     * Returns the symbol corresponding to the given currency.
     */
    public function formatCurrencyAsSymbol(Currency $currency): string
    {
        return Currencies::getSymbol($currency->getCode());
    }

    /**
     * @param string $currencyCode
     * @param string $locale
     *
     * @return \NumberFormatter
     */
    protected function getDefaultNumberFormatter($currencyCode, $locale = null)
    {
        if (null === $locale) {
            $locale = \Locale::getDefault();
        }
        $numberFormatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        $numberFormatter->setTextAttribute(\NumberFormatter::CURRENCY_CODE, $currencyCode);
        $numberFormatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, $this->decimals);

        return $numberFormatter;
    }
}
