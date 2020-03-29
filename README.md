# CoverdMoneyBundle

[![Latest Version](https://img.shields.io/packagist/v/coverd/money-bundle.svg)](https://github.com/Coverd/MoneyBundle/releases/latest)
[![Build Status](https://travis-ci.com/Coverd/MoneyBundle.svg?branch=master)](https://travis-ci.com/Coverd/MoneyBundle)

## Disclaimer

The original bundle is [CoverdMoneyBundle](https://github.com/TheBigBrainsCompany/CoverdMoneyBundle).
As this repository is not heavily maintained, we forked it & simplified it a lot by removing all features we don't need (basically, everything related to pair / ratio.

## Description

This bundle is used to integrate the [Money library from mathiasverraes](https://github.com/mathiasverraes/money) into a Symfony project.

This library is based on Fowler's [Money pattern](http://blog.verraes.net/2011/04/fowler-money-pattern-in-php/)

## Features

* Integrates money library from mathiasverraes
* Twig filters and PHP helpers for helping with money and currencies in templates
* Symfony form integration
* A configuration parser for specifying website used currencies
* Money formatter i18n

## Installation

Use [Composer](http://getcomposer.org/) and install with `composer require coverd/money-bundle`

Configure the bundle (configuration file should live in config/packages/coverd_money.yml):

```yaml
coverd_money:
    currencies: ["USD", "EUR"] # Choose all availables currencies
    reference_currency: "EUR" # Choose the default currency
    decimals: 2 # Choose the nuber of decimals (default: 2)
```

To use twig form theme:

```yaml
twig:
    form_themes:
        - 'MoneyBundle:Form:fields.html.twig'
```

To register the Doctrine Moeny type:

```yaml
doctrine:
    dbal:
        types:
            money: Coverd\MoneyBundle\Type\MoneyType
```


## Usage

### Money Library integration

```php
use Money\Money;

$fiveEur = Money::EUR(500);
$tenEur = $fiveEur->add($fiveEur);
list($part1, $part2, $part3) = $tenEur->allocate(array(1, 1, 1));
assert($part1->equals(Money::EUR(334)));
assert($part2->equals(Money::EUR(333)));
assert($part3->equals(Money::EUR(333)));
```

### Form integration

You have 3 new form types (under `Coverd\MoneyBundle\Form\Type` namespace):

* CurrencyType : asks for a currency among currencies defined in config.yml
* MoneyType : asks for an amount and a currency
* SimpleMoneyType : asks for an amount and sets the currency to the reference currency set in config.yml

Example :

```php
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

// I create my form
$form = $this->createFormBuilder()
    ->add('name', TextType::class)
    ->add('price', MoneyType::class, [
        'data' => Money::EUR(1000),
    ])
    ->getForm();
```

Manipulating the form

With `MoneyType` you can manipulate the form elements with

`amount_options` for the amount field, and `currency_options` for the currency field, fx if you want to change the label.

```php
$form = $this->createFormBuilder()
    ->add('price', MoneyType::class, [
        'data' => Money::EUR(1000),
        'amount_options' => [
            'label' => 'Amount',
        ],
        'currency_options' => [
            'label' => 'Currency',
        ],
    ])
    ->getForm();
```

With `CurrencyType` only `currency_options` can be used, and with `SimpleMoneyType` only `amount_options` can be used.

### Saving Money with Doctrine

#### Solution 1 : two fields in the database

Note that there are 2 columns in the DB table : $priceAmount and $priceCurrency and only one getter/setter : getPrice and setPrice.

The get/setPrice methods are dealing with these two columns transparently.

* Advantage : your DB is clean and you can do sql sum, group by, sort,... with the amount and the currency in two different columns in your db
* Disadvantage : it is ugly in the entity.

```php
<?php
namespace App\AdministratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;

/**
 * @ORM\Table("test_money")
 * @ORM\Entity
 */
class TestMoney
{
    /**
     * @var integer
     *
     * @ORM\Column(name="price_amount", type="integer")
     */
    private $priceAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="price_currency", type="string", length=64)
     */
    private $priceCurrency;

    public function getPrice(): Money
    {
        if (!$this->priceCurrency) {
            return null;
        }
        if (!$this->priceAmount) {
            return new Money(0, new Currency($this->priceCurrency));
        }
        return new Money($this->priceAmount, new Currency($this->priceCurrency));
    }

    public function setPrice(Money $price): void
    {
        $this->priceAmount = $price->getAmount();
        $this->priceCurrency = $price->getCurrency()->getCode();
    }
}
```

#### Solution 2 : use Doctrine type

There is only one string column in your DB table. The money object is manually serialized by
the new Doctrine type.

1.25€ is serialized in your DB by 'EUR 125'. *This format is stable. It won't change in future releases.*.

The new Doctrine type name is "money".

* Advantage : The entity is easy to create and use
* Disadvantage : it is more difficult to directly request the db in SQL.

```php
<?php
namespace App\AdministratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Table("test_money")
 * @ORM\Entity
 */
class TestMoney
{
    /**
     * @var Money
     *
     * @ORM\Column(name="price", type="money")
     */
    private $price;

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function setPrice(Money $price): void
    {
        $this->price = $price;
    }
}
```

### Money formatter

```php
<?php

namespace My\Controller\IndexController;

use Money\Money;
use Money\Currency;

class IndexController extends Controller
{
    public function myAction()
    {
        $moneyFormatter = $this->get('coverd_money.formatter.money_formatter');
        $price = new Money(123456789, new Currency('EUR'));

        \Locale::setDefault('fr_FR');
        $formatedPrice = $moneyFormatter->localizedFormatMoney($price); // 1 234 567,89 €
        $formatedPrice = $moneyFormatter->localizedFormatMoney($price, 'en'); // €1,234,567.89

        // old method (before v2.2)
        $formattedPrice = $moneyFormatter->formatMoney($price); // 1 234 567,89

        $formattedCurrency = $moneyFormatter->formatCurrency($price); // €
    }
}
```

### Twig integration

```twig
{{ $amount | money_localized_format('fr') }} => 1 234 567,89 €
{{ $amount | money_localized_format('en_US') }} => €1,234,567.89
{{ $amount | money_localized_format }} => depends on your default locale
{{ $amount | money_format }}
{{ $amount | money_as_float }}
{{ $amount.currency | currency_symbol }}
{{ $amount | money_format_currency }}
```

### MoneyManager : create a money object from a float

Create a money object from a float can be a bit tricky because of rounding issues.

```php
<?php

$moneyManager = $this->get("coverd_money.money_manager");
$money = $moneyManager->createMoneyFromFloat('2.5', 'USD');
$this->assertEquals("USD", $money->getCurrency()->getCode());
$this->assertEquals(250, $money->getAmount());
```

### Custom NumberFormatter in MoneyFormatter

The MoneyFormatter::localizedFormatMoney ( service 'coverd_money.formatter.money_formatter' ) use the php NumberFormatter class ( http://www.php.net/manual/en/numberformatter.formatcurrency.php ) to format money.

You can :

* give your own \NumberFormatter instance as a parameter of MoneyFormatter::localizedFormatMoney
* subclass the MoneyFormatter and rewrite the getDefaultNumberFormater method to set a application wide NumberFormatter
