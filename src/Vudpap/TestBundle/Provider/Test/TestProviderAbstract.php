<?php

namespace Vudpap\TestBundle\Provider\Test;


use Symfony\Component\DependencyInjection\ContainerAware;
use Vudpap\TestBundle\Entity\Test;
use Vudpap\TestBundle\Manager\TestManagerInterface;

abstract class TestProviderAbstract extends ContainerAware implements TestProviderInterface
{
    private $name;
    protected $actions = ['initPage', 'question', 'result'];
    protected $currentAction = null;
    /** @var  TestManagerInterface */
    protected $manager;

    public function __construct($name, TestManagerInterface $manager)
    {
        $this->name = $name;
        $this->manager = $manager;
    }

    /**
     * Get test unique name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get next action name
     *
     * @param $action
     * @return string|null
     */
    public function getNextAction($action = null)
    {
        if ($action == null) {
            return reset($this->actions);
        }

        $currentAction = reset($this->actions);
        while ($currentAction and $currentAction != $action) {
            $currentAction = next($this->actions);
        }

        return next($this->actions);
    }

    /**
     * Save current state
     *
     * @return string
     */
    abstract public function save();

    /**
     * Load state by $identifier
     *
     * @param $identifier
     * @return $this
     */
    abstract public function load($identifier);

    /**
     * Get current progress
     *
     * @return int
     */
    abstract public function progress();

    /**
     * Get test's url
     *
     * @param array $params
     * @return string
     */
    abstract public function getUrl($params = []);

    /**
     * Render html
     *
     * @return string
     */
    abstract public function render();
}
