<?php

namespace Vudpap\TestBundle\Manager;


use Symfony\Component\DependencyInjection\ContainerAware;
use Vudpap\TestBundle\Entity\Test;

class TestManager extends ContainerAware implements TestManagerInterface
{
    /**
     * Create new test
     *
     * @param $params
     * @return Test
     */
    public function create($params = null)
    {
        $testEntity = new Test();
        $testEntity->setTestName($params['testName']);
        $testEntity->setAction($params['action']);
        $testEntity->setCreatedAt(new \DateTime());

        $this->update($testEntity);

        // generate unique url key
        $testEntity->setUrl(sha1(time() . '_' . $testEntity->getId()));

        $this->update($testEntity);

        return $testEntity;
    }

    /**
     * @param Test $testEntity
     */
    public function update(&$testEntity)
    {
        $testEntity->setUpdatedAt(new \DateTime());

        $manager = $this->container->get('doctrine')->getManager();
        $manager->persist($testEntity);
        $manager->flush();
    }

    /**
     * @param $action
     * @param $urlId
     */
    public function setAction($action, $urlId)
    {
        $testEntity = $this->get($urlId);
        $testEntity->setAction($action);
        $this->update($testEntity);
    }

    /**
     * Get data from DB
     *
     * @param $urlId
     * @return Test
     */
    public function get($urlId)
    {
        return $this->container->get('doctrine')->getRepository('Vudpap\TestBundle\Entity\Test')->findOneByUrl($urlId);
    }
}
