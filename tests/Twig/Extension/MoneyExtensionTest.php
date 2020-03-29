<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Tests\Twig\Extension;

use Coverd\MoneyBundle\Formatter\MoneyFormatter;
use Coverd\MoneyBundle\Twig\Extension\MoneyExtension;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * @author Benjamin Dulau <benjamin@thebigbrainscompany.com>
 */
class MoneyExtensionTest extends TestCase
{
    /**
     * @var MoneyExtension
     */
    private $extension;

    /**
     * @var array
     */
    protected $variables;

    public function setUp(): void
    {
        \Locale::setDefault('fr_FR');

        $this->extension = new MoneyExtension(new MoneyFormatter(2));
        $this->variables = ['price' => new Money(123456789, new Currency('EUR'))];
    }

    /**
     * @dataProvider getMoneyTests
     */
    public function testMoney($template, $expected)
    {
        $this->assertEquals($expected, $this->getTemplate($template)->render($this->variables));
    }

    public function getMoneyTests()
    {
        return [
            ['{{ price|money_localized_format }}', '1 234 567,89 €'],
            ['{{ price|money_localized_format("en_US") }}', '€1,234,567.89'],
            ['{{ price|money_format }}', '1 234 567,89 €'],
            ['{{ price|money_format(".", ",") }}', '1,234,567.89 €'],
            ['{{ price|money_format_amount }}', '1 234 567,89'],
            ['{{ price|money_format_amount(".", ",") }}', '1,234,567.89'],
            ['{{ price|money_as_float }}', '1234567.89'],
            ['{{ price.currency|currency_symbol(".", ",") }}', '€'],
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
