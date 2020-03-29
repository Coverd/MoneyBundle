<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Tests\Config;

use Coverd\MoneyBundle\Money\MoneyManager;
use Coverd\MoneyBundle\Twig\Extension\MoneyExtension;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

/**
 * @group functionnal
 */
class ConfigTest extends WebTestCase
{
    /** @var \Symfony\Bundle\FrameworkBundle\Client */
    private $client;

    public function setUp(): void
    {
        parent::setUp();
        /* @var \Symfony\Bundle\FrameworkBundle\Client client */
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
        $this->assertEquals(['USD', 'EUR', 'CAD'], $currencies);

        $referenceCurrency = $this->client->getContainer()->getParameter('coverd_money.reference_currency');
        $this->assertEquals('EUR', $referenceCurrency);
    }

    public function testMoneyManager()
    {
        /** @var MoneyManager $moneyManager */
        $moneyManager = $this->client->getContainer()->get('coverd_money.money_manager');
        $money = $moneyManager->createMoneyFromFloat('2.5', 'USD');
        $this->assertEquals('USD', $money->getCurrency()->getCode());
        $this->assertEquals(2500, $money->getAmount()); // note : 3 decimals in config for theses tests
    }

    public function testCurrencyTwigExtension()
    {
        \Locale::setDefault('en');
        $this->assertInstanceOf(MoneyExtension::class, $this->client->getContainer()->get('coverd_money.twig.money'));
    }
}
