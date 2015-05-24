<?php

namespace Vudpap\TestBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vudpap\TestBundle\DependencyInjection\Compiler\TestProducerPass;

class TestBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TestProducerPass());
    }
}
