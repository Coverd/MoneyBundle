<?php

namespace Coverd\MoneyBundle\Tests\Twig\Extension;

use Money\Currency;
use Coverd\MoneyBundle\Formatter\MoneyFormatter;
use Coverd\MoneyBundle\Twig\Extension\CurrencyExtension;
use Twig\Loader\ArrayLoader;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

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
        \Locale::setDefault("fr_FR");
        $this->extension = new CurrencyExtension(new MoneyFormatter(2));
        $this->variables = array('currency' => new Currency('EUR'));
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
        return array(
            array('{{ currency|currency_name }}', 'EUR'),
            array('{{ currency|currency_symbol(".", ",") }}', '€'),
        );
    }

    protected function getTemplate($template)
    {
        $loader = new ArrayLoader(array('index' => $template));
        $twig = new Environment($loader, array('debug' => true, 'cache' => false));
        $twig->addExtension($this->extension);

        return $twig->load('index');
    }
}
