<?php

namespace Vudpap\TestBundle\Provider\Result;


use Symfony\Component\DependencyInjection\ContainerAware;
use Vudpap\TestBundle\Entity\Progress;

abstract class ResultProviderAbstract extends ContainerAware implements ResultProviderInterface
{
    /** @var  string */
    protected $template;

    /**
     * @param $template
     */
    public function __construct($template)
    {
        $this->template = $template;
    }

    public function render($params = [])
    {
        return $this->container->get('templating')->render(
            $this->template,
            $params
        );
    }

    /**
     * Do whatever you need to do for result processing
     *
     * @param $data
     * @return bool
     */
    abstract public function process($data = null);

    abstract public function serialize();

    abstract public function unserialize($serializedData);

    /**
     * By default it is not possible to go to previous step from result
     *
     * @return bool
     */
    public function goToPrevious()
    {
        return true;
    }

    /**
     * Gets current and total progress
     *
     * @return Progress
     */
    public function getProgress()
    {
        return new Progress();
    }
}
