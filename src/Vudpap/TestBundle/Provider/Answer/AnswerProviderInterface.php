<?php

namespace Vudpap\TestBundle\Provider\Answer;


interface AnswerProviderInterface
{
    /**
     * Get structure data for question
     *
     * @return mixed
     */
    public function getStructure();

    /**
     * Process answer data
     *
     * @param $data
     * @return bool
     */
    public function process($data);

    /**
     * Save answer
     *
     * @param $answer
     * @return $this
     */
    public function setAnswer($answer);

    /**
     * Return true if there is an answer
     *
     * @return bool
     */
    public function hasAnswer();

    /**
     * Return answered value if exists
     *
     * @return mixed
     */
    public function getAnswer();

    /**
     * Serialize current state
     *
     * @return string
     */
    public function serialize();

    /**
     * Load serialized state
     *
     * @param $serializedState
     * @return $this
     */
    public function unserialize($serializedState);
}
