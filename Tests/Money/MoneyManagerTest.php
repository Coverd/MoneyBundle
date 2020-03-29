<?php
namespace Coverd\MoneyBundle\Tests\Money;

use Money\Money;
use Coverd\MoneyBundle\Money\MoneyManager;
use Coverd\MoneyBundle\MoneyException;
use PHPUnit\Framework\TestCase;

/**
 * @group moneymanager
 */
class MoneyManagerTest extends TestCase
{
    /** @var  MoneyManager */
    protected $manager;

    public function setUp(): void
    {
        $this->manager = new MoneyManager("EUR", 2);
    }

    public function tearDown(): void
    {
    }

    public function testCreateMoneyFromFloat()
    {
        $money = $this->manager->createMoneyFromFloat(2.5);
        $this->assertEquals("EUR", $money->getCurrency()->getCode());
        $this->assertEquals(250, $money->getAmount());

        $money = $this->manager->createMoneyFromFloat(2.5, 'USD');
        $this->assertEquals("USD", $money->getCurrency()->getCode());
        $this->assertEquals(250, $money->getAmount());

        $money = $this->manager->createMoneyFromFloat(2.49999999999999);
        $this->assertEquals(250, $money->getAmount());

        $money = $this->manager->createMoneyFromFloat(2.529999999999);
        $this->assertEquals(253, $money->getAmount());
    }

}
