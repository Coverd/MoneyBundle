<?php

declare(strict_types=1);

namespace Coverd\MoneyBundle\Tests\Money;

use Coverd\MoneyBundle\Money\MoneyManager;
use PHPUnit\Framework\TestCase;

/**
 * @group moneymanager
 */
class MoneyManagerTest extends TestCase
{
    /** @var MoneyManager */
    protected $manager;

    protected function setUp(): void
    {
        $this->manager = new MoneyManager('EUR', 2);
    }

    protected function tearDown(): void
    {
    }

    public function testCreateMoneyFromFloat()
    {
        $money = $this->manager->createMoneyFromFloat(2.5);
        $this->assertEquals('EUR', $money->getCurrency()->getCode());
        $this->assertEquals(250, $money->getAmount());

        $money = $this->manager->createMoneyFromFloat(2.5, 'USD');
        $this->assertEquals('USD', $money->getCurrency()->getCode());
        $this->assertEquals(250, $money->getAmount());

        $money = $this->manager->createMoneyFromFloat(2.49999999999999);
        $this->assertEquals(250, $money->getAmount());

        $money = $this->manager->createMoneyFromFloat(2.529999999999);
        $this->assertEquals(253, $money->getAmount());
    }
}
