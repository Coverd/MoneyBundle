<?php
namespace Coverd\MoneyBundle\Tests\Config;

use Money\Money;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Coverd\MoneyBundle\Money\MoneyManager;
use Coverd\MoneyBundle\Pair\PairManagerInterface;
use Coverd\MoneyBundle\Twig\Extension\CurrencyExtension;
use Coverd\MoneyBundle\Twig\MoneyExtension;
use Coverd\MoneyBundle\Type\MoneyType;
use Doctrine\DBAL\Types\Type;
use Symfony\Bundle\FrameworkBundle\Console\Application;

/**
 * @group functionnal
 */
class ConfigTest extends WebTestCase
{
    /** @var  \Symfony\Bundle\FrameworkBundle\Client */
    private $client;

    public function setUp(): void
    {
        parent::setUp();
        /** @var \Symfony\Bundle\FrameworkBundle\Client client */
        $this->client = static::createClient();
    }

    protected function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        $application = new Application($this->client->getKernel());
        $application->setAutoExit(false);

        return $application->run(new StringInput($command));
    }

    public function testConfigParsing()
    {
        $currencies = $this->client->getContainer()->getParameter('coverd_money.currencies');
        $this->assertEquals(array("USD", "EUR", 'CAD'), $currencies);

        $referenceCurrency = $this->client->getContainer()->getParameter('coverd_money.reference_currency');
        $this->assertEquals("EUR", $referenceCurrency);
    }

    public function testMoneyManager()
    {
        /** @var MoneyManager $moneyManager */
        $moneyManager = $this->client->getContainer()->get("coverd_money.money_manager");
        $money = $moneyManager->createMoneyFromFloat('2.5', 'USD');
        $this->assertEquals("USD", $money->getCurrency()->getCode());
        $this->assertEquals(2500, $money->getAmount()); // note : 3 decimals in config for theses tests
    }

    public function testCurrencyTwigExtension()
    {
        \Locale::setDefault('en');
        $this->assertInstanceOf(CurrencyExtension::class, $this->client->getContainer()->get("coverd_money.twig.currency"));
    }
}
