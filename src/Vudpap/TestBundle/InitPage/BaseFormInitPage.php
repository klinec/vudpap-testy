<?php

namespace Vudpap\TestBundle\InitPage;


use Symfony\Component\HttpFoundation\Request;
use Vudpap\TestBundle\Provider\InitPage\InitPageProviderAbstract;

class BaseFormInitPage extends InitPageProviderAbstract
{
    /**
     * Set structure of answers
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function process(Request $request)
    {
        $this->form = $this->container->get('form.factory')->create($this->formType, $this->entity);
        $this->form->handleRequest($request);

        if ($this->form->isValid()) {
            $manager = $this->container->get('doctrine')->getManager();
            $manager->persist($this->entity);
            $manager->flush();

            return true;
        }

        return false;
    }

    public function serialize()
    {
        return $this->entity->getId();
    }

    public function unserialize($serializedData)
    {
        $this->entity = $this->container
            ->get('doctrine')
            ->getRepository(get_class($this->entity))
            ->find($serializedData);
    }
}
