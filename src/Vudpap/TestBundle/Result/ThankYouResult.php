<?php

namespace Vudpap\TestBundle\Result;


use Vudpap\TestBundle\Provider\Result\ResultProviderAbstract;

class ThankYouResult extends ResultProviderAbstract
{
    public function render($params = [])
    {
        $params['backwardDisabled'] = true;

        return parent::render($params);
    }

    public function process($data = null)
    {
        return true;
    }

    public function serialize()
    {
        return null;
    }

    public function unserialize($serializedData)
    {

    }
}
