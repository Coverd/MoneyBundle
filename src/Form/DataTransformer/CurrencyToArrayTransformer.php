<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Form\DataTransformer;

use Money\Currency;
use Money\Exception\UnknownCurrencyException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Transforms between a Currency and a string.
 */
class CurrencyToArrayTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }
        if (!$value instanceof Currency) {
            throw new UnexpectedTypeException($value, 'Currency');
        }

        return ['coverd_name' => $value->getCode()];
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return null;
        }
        if (!\is_array($value)) {
            throw new UnexpectedTypeException($value, 'array');
        }
        if (!isset($value['coverd_name'])) {
            return null;
        }
        try {
            return new Currency($value['coverd_name']);
        } catch (UnknownCurrencyException $e) {
            throw new TransformationFailedException($e->getMessage());
        }
    }
}
