<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Form\Type;

use Coverd\MoneyBundle\Form\DataTransformer\CurrencyToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for the Currency object.
 */
class CurrencyType extends AbstractType
{
    /** @var array of string (currency code like "USD", "EUR") */
    protected $currencyCodeList;
    /** @var string (currency code like "USD", "EUR") */
    protected $referenceCurrencyCode;

    /**
     * CurrencyType constructor.
     *
     * @param array  $currencyCodeList
     * @param string $referenceCurrencyCode
     */
    public function __construct($currencyCodeList, $referenceCurrencyCode)
    {
        $this->currencyCodeList = $currencyCodeList;
        $this->referenceCurrencyCode = $referenceCurrencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choiceList = [];
        foreach ($options['currency_choices'] as $currencyCode) {
            $choiceList[$currencyCode] = $currencyCode;
        }
        $builder->add('coverd_name', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array_merge([
            'choices' => $choiceList,
            'preferred_choices' => [$options['reference_currency']],
        ], $options['currency_options']));
        $builder->addModelTransformer(new CurrencyToArrayTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['reference_currency', 'currency_choices']);
        $resolver->setDefaults([
            'reference_currency' => $this->referenceCurrencyCode,
            'currency_choices' => $this->currencyCodeList,
            'currency_options' => [],
        ]);
        $resolver->setAllowedTypes('reference_currency', 'string');
        $resolver->setAllowedTypes('currency_choices', 'array');
        $resolver->setAllowedValues('reference_currency', $this->currencyCodeList);
        $resolver->setAllowedTypes('currency_options', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'coverd_currency';
    }
}
