<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Form\DataTransformer;

use Money\Currency;

/**
 * Transforms between a Money instance and an array.
 */
class SimpleMoneyToArrayTransformer extends MoneyToArrayTransformer
{
    /** @var string */
    private $currency;

    public function __construct(int $decimals)
    {
        parent::__construct($decimals);
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        $tab = parent::transform($value);
        if (!$tab) {
            return null;
        }
        unset($tab['coverd_currency']);

        return $tab;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (\is_array($value)) {
            $value['coverd_currency'] = new Currency($this->currency);
        }

        return parent::reverseTransform($value);
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }
}
