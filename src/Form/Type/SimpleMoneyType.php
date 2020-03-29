<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Form\Type;

use Coverd\MoneyBundle\Form\DataTransformer\SimpleMoneyToArrayTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for the Money object.
 */
class SimpleMoneyType extends MoneyType
{
    /** @var int */
    protected $decimals;

    /** @var array of string (currency code like "USD", "EUR") */
    protected $currencyCodeList;

    /** @var string (currency code like "USD", "EUR") */
    protected $referenceCurrencyCode;

    /**
     * @param int    $decimals
     * @param array  $currencyCodeList
     * @param string $referenceCurrencyCode
     */
    public function __construct(
        $decimals,
        $currencyCodeList,
        $referenceCurrencyCode
    ) {
        $this->decimals = (int) $decimals;
        $this->currencyCodeList = $currencyCodeList;
        $this->referenceCurrencyCode = $referenceCurrencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coverd_amount', 'Symfony\Component\Form\Extension\Core\Type\TextType', $options['amount_options'])
        ;

        $transformer = new SimpleMoneyToArrayTransformer($this->decimals);
        $transformer->setCurrency($options['currency']);

        $builder
            ->addModelTransformer($transformer)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'coverd_simple_money';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'currency' => $this->referenceCurrencyCode,
            'amount_options' => [],
        ]);
        $resolver->setAllowedTypes('currency', 'string');
        $resolver->setAllowedValues('currency', $this->currencyCodeList);
        $resolver->setAllowedTypes('amount_options', 'array');
    }
}
