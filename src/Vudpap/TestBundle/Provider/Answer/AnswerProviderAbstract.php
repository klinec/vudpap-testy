<?php

namespace Vudpap\TestBundle\Provider\Answer;


use Symfony\Component\DependencyInjection\ContainerAware;

abstract class AnswerProviderAbstract extends ContainerAware implements AnswerProviderInterface
{
    protected $answered;
    protected $structure;

    /**
     * Set structure of answers
     *
     * @param $structure
     * @return $this
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure data for question
     *
     * @return mixed
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Process answer data
     *
     * @param $question
     * @param $data
     * @return bool
     */
    abstract public function handleAnswer($question, $data);

    /**
     * Save answered question
     *
     * @param $question
     * @param $answer
     * @return $this
     */
    public function addAnswered($question, $answer)
    {
        $this->answered[$question] = $answer;

        return $this;
    }

    /**
     * Return true if question is answered
     *
     * @param $question
     * @return bool
     */
    public function isAnswered($question)
    {
        return isset($this->answered[$question]);
    }

    /**
     * Return answered value if exists
     *
     * @param $question
     * @return mixed
     */
    public function getAnswered($question)
    {
        return isset($this->answered[$question]) ? $this->answered[$question] : null;
    }

    /**
     * Get all answers
     *
     * @return mixed
     */
    public function getAnswers()
    {
        return $this->answered;
    }

    /**
     * Serialize current state
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->answered);
    }

    /**
     * Load serialized state
     *
     * @param $serializedState
     * @return $this
     */
    public function unserialize($serializedState)
    {
        $this->answered = unserialize($serializedState);

        return $this;
    }
}
