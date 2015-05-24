<?php

namespace Vudpap\TestBundle\Manager;


interface TestManagerInterface
{
    /**
     * Create new test entity
     *
     * @param $params
     * @return mixed
     */
    public function create($params = null);

    /**
     * Update test entity
     *
     * @param $testEntity
     */
    public function update(&$testEntity);

    /**
     * Get test entity from DB
     *
     * @param $identifier
     * @return mixed
     */
    public function get($identifier);
}
