<?php

namespace Vudpap\TestBundle\Provider\Test;


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
     * Get test's url
     *
     * @param array $params
     * @return string
     */
    public function getUrl($params = []);

    /**
     * Render html
     *
     * @return string
     */
    public function render();

    /**
     * Get current question order number
     *
     * @return int
     */
    public function progress();
}
