<?php

declare(strict_types=1);
/**
 * Created by Philippe Le Van.
 * Date: 01/07/13.
 */

namespace Coverd\MoneyBundle\Tests\Form\Type;

use Coverd\MoneyBundle\Form\Type\CurrencyType;
use Money\Currency;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class CurrencyTypeTest extends TypeTestCase
{
    private $currencyTypeClass = 'Coverd\MoneyBundle\Form\Type\CurrencyType';

    public function testBindValid()
    {
        $form = $this->factory->create($this->currencyTypeClass, null, []);
        $form->submit(['coverd_name' => 'EUR']);
        $this->assertEquals(new Currency('EUR'), $form->getData());
    }

    public function testSetData()
    {
        \Locale::setDefault('fr_FR');
        $form = $this->factory->create($this->currencyTypeClass, null, []);
        $form->setData(new Currency('USD'));
        $formView = $form->createView();

        $this->assertEquals('USD', $formView->children['coverd_name']->vars['value']);
    }

    public function testOptions()
    {
        $form = $this->factory->create($this->currencyTypeClass, null, [
            'currency_options' => [
                'label' => 'currency label',
            ],
        ]);

        $form->setData(new Currency('USD'));
        $formView = $form->createView();

        $this->assertEquals('USD', $formView->children['coverd_name']->vars['value']);
    }

    public function testOptionsFailsIfNotValid()
    {
        $this->expectException(UndefinedOptionsException::class);
        $this->expectExceptionMessageMatches('/this_does_not_exists/');

        $this->factory->create($this->currencyTypeClass, null, [
            'currency_options' => [
                'this_does_not_exists' => 'currency label',
            ],
        ]);
    }

    protected function getExtensions()
    {
        return [
            new PreloadedExtension(
                [new CurrencyType(['EUR', 'USD'], 'EUR')], []
            ),
        ];
    }
}
