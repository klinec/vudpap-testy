<?php

namespace Vudpap\TestBundle\Test;


use Symfony\Component\HttpFoundation\Request;
use Vudpap\TestBundle\Provider\InitPage\InitPageProviderInterface;
use Vudpap\TestBundle\Provider\Question\QuestionProviderInterface;
use Vudpap\TestBundle\Provider\Test\TestProviderAbstract;
use Vudpap\TestBundle\Entity\Test as TestEntity;
use Vudpap\TestBundle\Manager\TestManagerInterface;

class TestBase extends TestProviderAbstract
{
    private $currentQuestion = null;
    /** @var  InitPageProviderInterface */
    private $initPageProvider;
    /** @var  QuestionProviderInterface */
    private $questionProvider;
    private $answers;
    /** @var  TestEntity */
    private $testEntity;
    private $resultProvider;

    public function __construct(
        TestManagerInterface $manager,
        InitPageProviderInterface $initPageProvider,
        QuestionProviderInterface $questionProvider,
        $name
    ) {
        parent::__construct($name, $manager);

        $this->initPageProvider = $initPageProvider;
        $this->questionProvider = $questionProvider;
    }

    public function init()
    {
        /** @var TestEntity $testEntity */
        $this->testEntity = $this->manager->create(
            [
                'testName' => $this->getName(),
                'action' => $this->getNextAction()
            ]
        );

        return $this->testEntity->getUrl();
    }
    /**
     * Save current state
     *
     * @return string
     */
    public function save()
    {
        $manager = $this->container->get('doctrine')->getManager();
        $manager->persist($this->testEntity);
        $manager->flush();
    }

    /**
     * Load state by $identifier
     *
     * @param $identifier
     * @return $this
     */
    public function load($identifier)
    {
        /** @var $this->testEntity testEntity */
        $this->testEntity = $this->manager->get($identifier);

        if (empty($this->testEntity)) {
            return false;
        }

        return true;
    }

    /**
     * Get current progress
     *
     * @return int
     */
    public function progress()
    {

    }

    public function process(Request $request)
    {
        switch($this->testEntity->getAction()) {
            case 'initPage':
                if ($this->initPageProvider->process($request)) {
                    $this->testEntity->setAction('question');
                }

                $this->testEntity->setInitPage($this->initPageProvider->serialize());
                break;
            case 'question':
                $this->questionProvider->unserialize(
                    $this->testEntity->getQuestion(),
                    $this->testEntity->getAnswer()
                );

        }

        $this->save();
    }

    /**
     * Render html
     *
     * @return string
     */
    public function render()
    {
        $result = '';

        switch($this->testEntity->getAction()) {
            case 'initPage':
                $result = $this->initPageProvider->render();
                break;
            case 'question':
                $result = $this->questionProvider->render();
                break;
        }

        return $result;
    }

    public function getUrl($params = [])
    {
        $params['testName'] = $this->getName();

        return $this->container->get('router')->generate('basic_test', $params);
    }

    public function getCurrentQuestion()
    {
        return $this->currentQuestion;
    }

    public function getPreviousQuestion()
    {
        $previousQuestion = $this->currentQuestion - 1;

        return isset($this->questions[$previousQuestion]) ? $previousQuestion : null;
    }

    public function getNextQuestion()
    {
        $nextQuestion = $this->currentQuestion + 1;

        return isset($this->questions[$nextQuestion]) ? $nextQuestion : null;
    }
}