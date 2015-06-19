<?php

namespace Vudpap\TestBundle\Question;


use Vudpap\TestBundle\Provider\Question\QuestionProviderAbstract;

class OnePerPageQuestion extends QuestionProviderAbstract
{
    /**
     * @param array $params
     * @return string
     */
    public function render($params = [])
    {
        return $this->container->get('templating')->render(
            $this->template,
            array_merge(
                $params,
                [
                    'questionId' => $this->getCurrentQuestion(),
                    'questionText' => $this->getQuestion(),
                    'answer' => $this->getAnswerProvider()->render($params)
                ]
            )
        );
    }

    /**
     * Process question data
     *
     * @param $data
     * @return bool
     */
    public function process($data = null)
    {
        if ($this->getAnswerProvider()->process($data)) {

            $this->answers[$this->getCurrentQuestion()] = $this->getAnswerProvider()->serialize();

            if ($this->isLastQuestion()) {
                return true;
            }

            $this->goToNext();
        }

        return false;
    }

    public function serialize()
    {
        $result = [
            'currentQuestion' => $this->getCurrentQuestion(),
            'answers' => $this->answers
        ];

        return json_encode($result);
    }

    public function unserialize($serializedData)
    {
        $result = json_decode($serializedData, true);

        if (isset($result['currentQuestion'])) {
            $this->setCurrentQuestion($result['currentQuestion']);
        }

        if (isset($result['answers'])) {
            $this->answers = $result['answers'];
        }

        $this->loadAnswer();

        return $this;
    }
}
