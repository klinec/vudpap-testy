<?php

namespace Vudpap\TestBundle\Provider\InitPage;


use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Form\AbstractType;
use Vudpap\TestBundle\Entity\Progress;

abstract class InitPageProviderAbstract extends ContainerAware implements InitPageProviderInterface
{
    protected $entity;
    /** @var  AbstractType */
    protected $formType;
    /** @var  \Symfony\Component\Form\Form */
    protected $form;
    /** @var  string */
    protected $template;

    /**
     * @param $entity
     * @param AbstractType $formType
     * @param $template
     */
    public function __construct($template, $entity = null, AbstractType $formType = null)
    {
        if ($entity) {
            $this->entity = new $entity();
        }
        $this->formType = $formType;
        $this->template = $template;
    }

    /**
     * Set structure of answers
     *
     * @param \Symfony\Component\HttpFoundation\Request $data
     * @return bool
     */
    public function process($data = null)
    {
        $this->form = $this->container->get('form.factory')->create($this->formType, $this->entity);
        /** @var \Symfony\Component\HttpFoundation\Request $data */
        $this->form->handleRequest($data);

        if ($this->form->isValid()) {
            $manager = $this->container->get('doctrine')->getManager();
            $manager->persist($this->entity);
            $manager->flush();

            return true;
        }

        return false;
    }

    public function render($params = [])
    {
        return $this->container->get('templating')->render(
            $this->template,
            array_merge(
                $params,
                ['form' => $this->form->createView()]
            )
        );
    }

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
