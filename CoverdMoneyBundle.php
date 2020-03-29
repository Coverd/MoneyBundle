<?php
namespace Coverd\MoneyBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Coverd\MoneyBundle\DependencyInjection\Compiler\PairHistoryCompilerPass;
use Coverd\MoneyBundle\DependencyInjection\Compiler\RatioProviderCompilerPass;
use Coverd\MoneyBundle\DependencyInjection\Compiler\StorageCompilerPass;
use Coverd\MoneyBundle\DependencyInjection\Compiler\DoctrineTypeCompilerPass;

class CoverdMoneyBundle extends Bundle
{
}
