<?php

namespace Vudpap\TestBundle\Provider\Question;


use Symfony\Component\DependencyInjection\ContainerAware;
use Vudpap\TestBundle\Entity\Progress;
use Vudpap\TestBundle\Provider\Answer\AnswerProviderInterface;

abstract class QuestionProviderAbstract extends ContainerAware implements QuestionProviderInterface
{
    protected $answers;
    protected $template;
    protected $questions;
    protected $currentQuestion = 1;

    public function __construct($questions, $template)
    {
        $this->questions = $questions;
        $this->template = $template;
    }

    public function getQuestion($questionId = null)
    {
        if ($questionId == null) {
            $questionId = $this->currentQuestion;
        }

        return key(array_slice($this->questions, $questionId - 1, 1));
    }

    /**
     * @return int
     */
    public function getCurrentQuestion()
    {
        return $this->currentQuestion;
    }

    /**
     * @param int $currentQuestion
     */
    public function setCurrentQuestion($currentQuestion)
    {
        $this->currentQuestion = $currentQuestion;
    }

    public function isLastQuestion($questionId = null)
    {
        if ($questionId == null) {
            $questionId = $this->currentQuestion;
        }

        return count($this->questions) == $questionId;
    }

    public function isFistQuestion($questionId = null)
    {
        if ($questionId == null) {
            $questionId = $this->currentQuestion;
        }

        return 1 === $questionId;
    }

    public function goToNext()
    {
        if (!$this->isLastQuestion()) {
            $this->currentQuestion++;
            $this->loadAnswer();
        }

        return $this;
    }

    /**
     * @param $questionId
     * @return AnswerProviderInterface
     */
    public function getAnswerProvider($questionId = null)
    {
        if ($questionId == null) {
            $questionId = $this->currentQuestion;
        }

        /** @var string $answerProvider */
        $answerProviderName = current(array_slice($this->questions, $questionId - 1, 1));

        return $this->container->get($answerProviderName);
    }

    public function loadAnswer($questionId = null)
    {
        if ($questionId == null) {
            $questionId = $this->getCurrentQuestion();
        }

        $this->getAnswerProvider()->unserialize(
            isset($this->answers[$questionId]) ? $this->answers[$questionId] : null
        );
    }

    abstract public function process($data = null);

    abstract public function render($params = []);

    abstract public function serialize();

    abstract public function unserialize($serializedData);

    /**
     * Sets $this->currentQuestion to previous one
     *
     * @return bool returns false if it is not possible to move to previous state
     */
    public function goToPrevious()
    {
        if ($this->isFistQuestion()) {
            return false;
        }

        $this->currentQuestion--;
        $this->loadAnswer();

        return true;
    }

    /**
     * Gets current and total progress
     *
     * @return Progress
     */
    public function getProgress()
    {
        return new Progress($this->getCurrentQuestion(), count($this->questions));
    }
}
