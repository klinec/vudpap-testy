<?php

namespace Vudpap\TestBundle\Provider;

use Vudpap\TestBundle\Provider\Test\TestProviderInterface;

/**
 * Test loader
 */
class TestLoader
{
    /**
     * @var array
     */
    private $testContainer = array();
    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Registers new Test to container
     *
     * @param TestProviderInterface $test
     */
    public function addTest(TestProviderInterface $test)
    {
        $this->testContainer[$test->getName()] = $test;
    }

    /**
     * Get Test by name
     *
     * @param string $name
     * @return bool|TestProviderInterface
     */
    public function getTest($name)
    {
        if (isset($this->testContainer[$name])) {
            return $this->testContainer[$name];
        }

        return false;
    }
}
