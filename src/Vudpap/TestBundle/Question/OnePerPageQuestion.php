<?php

namespace Vudpap\TestBundle\Question;


use Vudpap\TestBundle\Provider\Question\QuestionProviderAbstract;

class OnePerPageQuestion extends QuestionProviderAbstract
{
    /**
     * Render html template for question
     *
     * @return string
     */
    public function render()
    {
        return $this->container->get('templating')->render(
            $this->template,
            [
                'questionId' => $this->getCurrentQuestion(),
                'questionText' => $this->getQuestion(),
                'answer' => $this->getAnswerProvider()->render($this->getCurrentQuestion())
            ]
        );
    }
}
