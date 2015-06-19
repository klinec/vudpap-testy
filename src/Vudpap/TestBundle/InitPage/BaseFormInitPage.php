<?php

namespace Vudpap\TestBundle\InitPage;


use Symfony\Component\HttpFoundation\Request;
use Vudpap\TestBundle\Provider\InitPage\InitPageProviderAbstract;

class BaseFormInitPage extends InitPageProviderAbstract
{
    public function serialize()
    {
        return $this->entity->getId();
    }

    public function unserialize($serializedData)
    {
        if (!empty($serializedData)) {
            $this->entity = $this->container
                ->get('doctrine')
                ->getRepository(get_class($this->entity))
                ->find($serializedData);
        }
    }
}
