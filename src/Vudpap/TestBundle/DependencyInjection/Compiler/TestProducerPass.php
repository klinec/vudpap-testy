<?php

namespace Vudpap\TestBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This class injects symfony compiler pass and registers 
 * services with a provider tag
 */
class TestProducerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('test.loader')) {
            return;
        }

        $definition = $container->getDefinition('test.loader');

        $tagged =$container->findTaggedServiceIds('vudpap.test');
        foreach ($tagged as $key => $tags) {
            $definition->addMethodCall('addTest', array(new Reference($key)));
        }
    }
}
