<?php

namespace Vudpap\TestBundle\Provider\InitPage;


use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;

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

    public function render($params = [])
    {
        return $this->container->get('templating')->render(
            $this->template,
            array_merge(
                ['form' => $this->form->createView()],
                $params
            )
        );
    }

    abstract public function serialize();

    abstract public function unserialize($serializedData);
}
