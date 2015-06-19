<?php

namespace Vudpap\TestBundle\Provider\Answer;


use Symfony\Component\DependencyInjection\ContainerAware;

abstract class AnswerProviderAbstract extends ContainerAware implements AnswerProviderInterface
{
    protected $template;
    protected $answer;
    protected $structure;

    public function __construct($structure, $template)
    {
        $this->structure = $structure;
        $this->template = $template;
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
     * @param $data
     * @return bool
     */
    abstract public function process($data);

    /**
     * Save answer
     *
     * @param $answer
     * @return $this
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Return true if there is an answer
     *
     * @return bool
     */
    public function hasAnswer()
    {
        return !empty($this->answer);
    }

    /**
     * Return answered value if exists
     *
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Serialize current state
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->answer);
    }

    /**
     * Load serialized state
     *
     * @param $serializedState
     * @return $this
     */
    public function unserialize($serializedState)
    {
        $this->answer = unserialize($serializedState);

        return $this;
    }
}
