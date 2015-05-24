<?php

namespace Vudpap\TestBundle\Provider\Question;


use Symfony\Component\DependencyInjection\ContainerAware;
use Vudpap\TestBundle\Provider\Answer\AnswerProviderInterface;

abstract class QuestionProviderAbstract extends ContainerAware implements QuestionProviderInterface
{
    protected $questions;
    protected $currentQuestion = 1;

    public function __construct($questions)
    {
        $this->questions = $questions;
    }

    public function getQuestion($questionId)
    {
        return key(array_slice($this->questions, $questionId - 1, 1));
    }

    /**
     * @param $questionId
     * @return AnswerProviderInterface
     */
    public function getAnswerProvider($questionId)
    {
        /** @var AnswerProviderInterface $answerProvider */
        list($answerProvider) = array_slice($this->questions, $questionId - 1, 1);

        return $answerProvider;
    }

    public function serialize()
    {
        return $this->currentQuestion;
    }

    public function unserialize($questionData, $answerData)
    {
        $this->currentQuestion = empty($questionData) ? 1 : $questionData;
        $this->getAnswerProvider($this->currentQuestion)->unserialize($answerData);

        return $this;
    }
}
