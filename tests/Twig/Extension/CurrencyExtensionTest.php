<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Tests\Twig\Extension;

use Coverd\MoneyBundle\Formatter\MoneyFormatter;
use Coverd\MoneyBundle\Twig\Extension\CurrencyExtension;
use Money\Currency;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * @author Benjamin Dulau <benjamin@thebigbrainscompany.com>
 */
class CurrencyExtensionTest extends TestCase
{
    /**
     * @var CurrencyExtension
     */
    private $extension;

    /**
     * @var array
     */
    protected $variables;

    public function setUp(): void
    {
        \Locale::setDefault('fr_FR');
        $this->extension = new CurrencyExtension(new MoneyFormatter(2));
        $this->variables = ['currency' => new Currency('EUR')];
    }

    /**
     * @dataProvider getCurrencyTests
     */
    public function testCurrency($template, $expected)
    {
        $this->assertEquals($expected, $this->getTemplate($template)->render($this->variables));
    }

    public function getCurrencyTests()
    {
        return [
            ['{{ currency|currency_name }}', 'EUR'],
            ['{{ currency|currency_symbol(".", ",") }}', '€'],
        ];
    }

    protected function getTemplate($template)
    {
        $loader = new ArrayLoader(['index' => $template]);
        $twig = new Environment($loader, ['debug' => true, 'cache' => false]);
        $twig->addExtension($this->extension);

        return $twig->load('index');
    }
}
