<?php

namespace Vudpap\TestBundle\Provider\Answer;


interface AnswerProviderInterface
{
    /**
     * Set structure of answers
     *
     * @param $structure
     * @return $this
     */
    public function setStructure($structure);

    /**
     * Get structure data for question
     *
     * @return mixed
     */
    public function getStructure();

    /**
     * Process answer data
     *
     * @param $question
     * @param $data
     * @return bool
     */
    public function handleAnswer($question, $data);

    /**
     * Save answered question
     *
     * @param $question
     * @param $answer
     * @return $this
     */
    public function addAnswered($question, $answer);

    /**
     * Return true if question is answered
     *
     * @param $question
     * @return bool
     */
    public function isAnswered($question);

    /**
     * Return answered value if exists
     *
     * @param $question
     * @return mixed
     */
    public function getAnswered($question);

    /**
     * Get all answers
     *
     * @return mixed
     */
    public function getAnswers();

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
