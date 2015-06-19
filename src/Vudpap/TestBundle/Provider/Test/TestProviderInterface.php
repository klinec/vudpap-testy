<?php

namespace Vudpap\TestBundle\Provider\Test;


use Vudpap\TestBundle\Entity\Progress;

interface TestProviderInterface
{
    /**
     * Save current state
     *
     * @return string
     */
    public function save();

    /**
     * Load serialized state
     *
     * @param $identifier
     * @return $this
     */
    public function load($identifier);

    /**
     * Get test unique name
     *
     * @return string
     */
    public function getName();

    /**
     * Get next action name
     *
     * @param $action
     * @return string|null
     */
    public function getNextAction($action = null);

    /**
     * Get previous action name
     *
     * @param $action
     * @return string|null
     */
    public function getPreviousAction($action = null);

    /**
     * Get test's url for next action
     *
     * @param array $params
     * @return string
     */
    public function getUrlNext($params = []);

    /**
     * Get test's url for previous action
     *
     * @param array $params
     * @return string
     */
    public function getUrlPrev($params = []);

    /**
     * Render html
     *
     * @return string
     */
    public function render();

    /**
     * Gets current and total progress
     *
     * @return Progress
     */
    public function getProgress();
}
