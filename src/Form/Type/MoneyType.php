<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Form\Type;

use Coverd\MoneyBundle\Form\DataTransformer\MoneyToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoneyType extends AbstractType
{
    private $decimals;

    public function __construct(int $decimals)
    {
        $this->decimals = $decimals;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('coverd_amount', 'Symfony\Component\Form\Extension\Core\Type\TextType', $options['amount_options'])
            ->add('coverd_currency', $options['currency_type'], $options['currency_options'])
            ->addModelTransformer(
                new MoneyToArrayTransformer($this->decimals)
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => null,
                'currency_type' => 'Coverd\MoneyBundle\Form\Type\CurrencyType',
                'amount_options' => [],
                'currency_options' => [],
            ])
            ->setAllowedTypes(
                'currency_type',
                [
                    'string',
                    'Coverd\MoneyBundle\Form\Type\CurrencyType',
                ]
            )
            ->setAllowedTypes(
                'amount_options',
                'array'
            )
            ->setAllowedTypes(
                'currency_options',
                'array'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'coverd_money';
    }
}
