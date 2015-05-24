<?php

namespace Vudpap\TestBundle\Provider\InitPage;


use Symfony\Component\HttpFoundation\Request;

interface InitPageProviderInterface
{
    /**
     * Set structure of answers
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\Form\Form
     */
    public function process(Request $request);

    public function render($params = []);

    public function serialize();

    public function unserialize($serializedData);
}
