<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Form\Type;

use Coverd\MoneyBundle\Form\DataTransformer\SimpleMoneyToArrayTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleMoneyType extends MoneyType
{
    private $decimals;
    private $currencyCodeList;
    private $referenceCurrencyCode;

    public function __construct(int $decimals, array $currencyCodeList, string $referenceCurrencyCode)
    {
        parent::__construct($decimals);

        $this->decimals = $decimals;
        $this->currencyCodeList = $currencyCodeList;
        $this->referenceCurrencyCode = $referenceCurrencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
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

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
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
