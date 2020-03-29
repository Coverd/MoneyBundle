<?php

declare(strict_types=1);
/**
 * Created by Philippe Le Van.
 * Date: 01/07/13.
 */

namespace Coverd\MoneyBundle\Tests\Form\Type;

use Coverd\MoneyBundle\Form\Type\SimpleMoneyType;
use Money\Money;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class SimpleMoneyTypeTest extends TypeTestCase
{
    private $simpleMoneyTypeClass = 'Coverd\MoneyBundle\Form\Type\SimpleMoneyType';

    public function testBindValid()
    {
        $form = $this->factory->create($this->simpleMoneyTypeClass, null, []);
        $form->submit([
            'coverd_amount' => '12',
        ]);
        $this->assertEquals(Money::EUR(1200), $form->getData());
    }

    public function testBindValidDecimals()
    {
        \Locale::setDefault('fr_FR');
        $form = $this->factory->create($this->simpleMoneyTypeClass, null, []);
        $form->submit([
            'coverd_amount' => '1,2',
        ]);
        $this->assertEquals(Money::EUR(1200), $form->getData());
    }

    public function testBindDecimalValid()
    {
        \Locale::setDefault('fr_FR');
        $form = $this->factory->create($this->simpleMoneyTypeClass, null, []);
        $form->submit([
            'coverd_amount' => '12,5',
        ]);
        $this->assertEquals(Money::EUR(1250), $form->getData());
    }

    public function testGreaterThan1000Valid()
    {
        \Locale::setDefault('fr_FR');
        $form = $this->factory->create($this->simpleMoneyTypeClass, null, []);
        $form->submit([
            'coverd_amount' => '1 252,5',
        ]);
        $this->assertEquals(Money::EUR(125250), $form->getData());
    }

    public function testSetData()
    {
        \Locale::setDefault('fr_FR');
        $form = $this->factory->create($this->simpleMoneyTypeClass, null, []);
        $form->setData(Money::EUR(120));
        $formView = $form->createView();

        $this->assertEquals('1,20', $formView->children['coverd_amount']->vars['value']);
    }

    public function testOptions()
    {
        \Locale::setDefault('fr_FR');
        $form = $this->factory->create($this->simpleMoneyTypeClass, null, [
            'amount_options' => [
                'label' => 'Amount',
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

        $this->factory->create($this->simpleMoneyTypeClass, null, [
            'amount_options' => [
                'this_does_not_exists' => 'Amount',
            ],
        ]);
    }

    protected function getExtensions()
    {
        //This is probably not ideal, but I'm not sure how to set up the pair manager
        // with different decimals for different tests in Symfony 3.0
        $decimals = 2;
        $currencies = ['EUR', 'USD'];
        $referenceCurrency = 'EUR';

        if ('testBindValidDecimals' === $this->getName()) {
            $decimals = 3;
        }

        return [
            new PreloadedExtension(
                [new SimpleMoneyType($decimals, $currencies, $referenceCurrency)], []
            ),
        ];
    }

    public function testOverrideCurrency()
    {
        \Locale::setDefault('fr_FR');
        $form = $this->factory->create($this->simpleMoneyTypeClass, null, ['currency' => 'USD']);
        $form->submit([
            'coverd_amount' => '1 252,5',
        ]);
        $this->assertEquals(Money::USD(125250), $form->getData());
    }
}
