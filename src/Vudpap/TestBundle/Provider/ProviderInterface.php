<?php

namespace Vudpap\TestBundle\Provider;


use Vudpap\TestBundle\Entity\Progress;

interface ProviderInterface
{
    /**
     * Set structure of answers
     *
     * @param mixed $data
     * @return \Symfony\Component\Form\Form
     */
    public function process($data = null);

    public function render($params = []);

    public function serialize();

    public function unserialize($serializedData);

    /**
     * If there are multiple steps, this will set all the data as it would be previous step
     *
     * @return bool returns false if it is not possible to move to previous state
     */
    public function goToPrevious();

    /**
     * Gets current and total progress
     *
     * @return Progress
     */
    public function getProgress();
}
