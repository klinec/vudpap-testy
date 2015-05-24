<?php

namespace Vudpap\TestBundle\Question;


use Vudpap\TestBundle\Provider\Question\QuestionProviderAbstract;

class OnePerPageQuestion extends QuestionProviderAbstract
{
    /**
     * Render html template for question
     *
     * @param $template
     * @return string
     */
    public function render($template)
    {
        return $this->container->get('templating')->render(
            $template,
            [
                'questionId' => $this->getCurrentQuestion(),
                'questionText' => $this->show()
            ]
        );
    }
}
