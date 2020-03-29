<?php

declare(strict_types=1);
/**
 * Created by Philippe Le Van.
 * Date: 01/07/13.
 */

namespace Coverd\MoneyBundle\Tests\Form\Type;

use Coverd\MoneyBundle\Form\Type\CurrencyType;
use Coverd\MoneyBundle\Form\Type\MoneyType;
use Money\Money;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class MoneyTypeTest extends TypeTestCase
{
    private $currencyTypeClass = 'Coverd\MoneyBundle\Form\Type\CurrencyType';
    private $moneyTypeClass = 'Coverd\MoneyBundle\Form\Type\MoneyType';

    public function testBindValid()
    {
        $form = $this->factory->create($this->moneyTypeClass, null, [
            'currency_type' => $this->currencyTypeClass,
        ]);
        $form->submit([
            'coverd_currency' => ['coverd_name' => 'EUR'],
            'coverd_amount' => '12',
        ]);
        $this->assertEquals(Money::EUR(1200), $form->getData());
    }

    public function testBindDecimalValid()
    {
        \Locale::setDefault('fr_FR');
        $form = $this->factory->create($this->moneyTypeClass, null, [
            'currency_type' => $this->currencyTypeClass,
        ]);
        $form->submit([
            'coverd_currency' => ['coverd_name' => 'EUR'],
            'coverd_amount' => '12,5',
        ]);
        $this->assertEquals(Money::EUR(1250), $form->getData());
    }

    public function testGreaterThan1000Valid()
    {
        \Locale::setDefault('fr_FR');
        $form = $this->factory->create($this->moneyTypeClass, null, [
            'currency_type' => $this->currencyTypeClass,
        ]);
        $form->submit([
            'coverd_currency' => ['coverd_name' => 'EUR'],
            'coverd_amount' => '1 252,5',
        ]);
        $this->assertEquals(Money::EUR(125250), $form->getData());
    }

    public function testSetData()
    {
        \Locale::setDefault('fr_FR');
        $form = $this->factory->create($this->moneyTypeClass, null, [
            'currency_type' => $this->currencyTypeClass,
        ]);
        $form->setData(Money::EUR(120));
        $formView = $form->createView();

        $this->assertEquals('1,20', $formView->children['coverd_amount']->vars['value']);
    }

    public function testOptions()
    {
        \Locale::setDefault('fr_FR');
        $form = $this->factory->create($this->moneyTypeClass, null, [
            'currency_type' => $this->currencyTypeClass,
            'amount_options' => [
                'label' => 'Amount',
            ],
            'currency_options' => [
                'label' => 'Currency',
            ],
        ]);
        $form->setData(Money::EUR(120));
        $formView = $form->createView();

        $this->assertEquals('1,20', $formView->children['coverd_amount']->vars['value']);
    }

    public function testOptionsFailsIfNotValid()
    {
        $this->expectException(UndefinedOptionsException::class);
        $this->expectExceptionMessageMatches('/this_does_not_exists/');

        $this->factory->create($this->moneyTypeClass, null, [
            'currency_type' => $this->currencyTypeClass,
            'amount_options' => [
                'this_does_not_exists' => 'Amount',
            ],
            'currency_options' => [
                'label' => 'Currency',
            ],
        ]);
    }

    protected function getExtensions()
    {
        return [
            new PreloadedExtension(
                [new CurrencyType(['EUR', 'USD'], 'EUR'), new MoneyType(2)], []
            ),
        ];
    }
}
