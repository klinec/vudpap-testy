<?php

namespace Vudpap\TestBundle\Test;


use Symfony\Component\HttpFoundation\Request;
use Vudpap\TestBundle\Entity\Progress;
use Vudpap\TestBundle\Provider\InitPage\InitPageProviderInterface;
use Vudpap\TestBundle\Provider\Question\QuestionProviderInterface;
use Vudpap\TestBundle\Provider\Result\ResultProviderInterface;
use Vudpap\TestBundle\Provider\Test\TestProviderAbstract;
use Vudpap\TestBundle\Entity\Test as TestEntity;
use Vudpap\TestBundle\Manager\TestManagerInterface;

class TestBase extends TestProviderAbstract
{
    /** @var  TestEntity */
    private $testEntity;
    /** @var  InitPageProviderInterface */
    private $initPageProvider;
    /** @var  QuestionProviderInterface */
    private $questionProvider;
    /** @var  ResultProviderInterface */
    private $resultProvider;

    public function __construct(
        TestManagerInterface $manager,
        InitPageProviderInterface $initPageProvider,
        QuestionProviderInterface $questionProvider,
        ResultProviderInterface $resultProvider,
        $name
    ) {
        parent::__construct($name, $manager);

        $this->initPageProvider = $initPageProvider;
        $this->questionProvider = $questionProvider;
        $this->resultProvider = $resultProvider;
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

        // TODO: rename to UrlId
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

        $this->loadAction();

        return true;
    }

    public function setAction($action)
    {
        $this->testEntity->setAction($action);
        $this->loadAction();
    }

    public function loadAction($action = '')
    {
        if (empty($action)) {
            $action = $this->testEntity->getAction();
        }
        $providerVariable = $action . 'Provider';
        $providerDataGetter = 'get' . ucfirst($action);

        $this->$providerVariable->unserialize(
            $this->testEntity->$providerDataGetter()
        );
    }

    /**
     * Gets current and total progress
     *
     * @return Progress
     */
    public function getProgress()
    {
        $total = 0;
        $current = 0;
        // $current will be increased only till current action is reached
        $wasCurrent = false;

        foreach ($this->actions as $action) {
            $providerVariable = $action . 'Provider';

            /** @var Progress $actionProgress */
            $actionProgress =  $this->$providerVariable->getProgress();
            $total += $actionProgress->getTotal();

            if ($this->testEntity->getAction() == $action) {
                $wasCurrent = true;
                $current += $actionProgress->getCurrent();
            }

            // it's before current action, so it means that $action was already done
            if (!$wasCurrent) {
                $current += $actionProgress->getTotal();
            }
        }

        return new Progress($current, $total);
    }

    public function process(Request $request)
    {
        switch($this->testEntity->getAction()) {
            case 'initPage':
                if ($this->initPageProvider->process($request)) {
                    $this->setAction('question');
                }

                $this->testEntity->setInitPage($this->initPageProvider->serialize());
                break;
            case 'question':
                if ($this->questionProvider->process($request)) {
                    $this->setAction('result');
                }

                $this->testEntity->setQuestion($this->questionProvider->serialize());
                break;
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
                $result = $this->initPageProvider->render(
                    [
                        'urlNext' => $this->getUrlNext(),
                        'urlPrev' => $this->getUrlPrev(),
                        'testName' => $this->getName(),
                        'progress' => $this->getProgress(),
                        'backwardDisabled' => $this->testEntity->getAction() == reset($this->actions),
                        'forwardDisabled' => $this->testEntity->getAction() == end($this->actions),
                    ]
                );
                break;
            case 'question':
                $result = $this->questionProvider->render(
                    [
                        'urlNext' => $this->getUrlNext(),
                        'urlPrev' => $this->getUrlPrev(),
                        'testName' => $this->getName(),
                        'progress' => $this->getProgress(),
                        'backwardDisabled' => $this->testEntity->getAction() == reset($this->actions),
                        'forwardDisabled' => $this->testEntity->getAction() == end($this->actions),
                    ]
                );
                break;
            case 'result':
                $result = $this->resultProvider->render(
                    [
                        'urlNext' => $this->getUrlNext(),
                        'urlPrev' => $this->getUrlPrev(),
                        'testName' => $this->getName(),
                        'progress' => $this->getProgress(),
                        'backwardDisabled' => $this->testEntity->getAction() == reset($this->actions),
                        'forwardDisabled' => $this->testEntity->getAction() == end($this->actions),
                    ]
                );
                break;
        }

        return $result;
    }

    public function getUrlNext($params = [])
    {
        $params['testName'] = $this->getName();
        $params['testUrlId'] = $this->testEntity->getUrl();

        return $this->container->get('router')->generate('basic_test', $params);
    }

    public function getUrlPrev($params = [])
    {
        $params['testName'] = $this->getName();
        $params['testUrlId'] = $this->testEntity->getUrl();
        $params['previous'] = 'previous';

        return $this->container->get('router')->generate('basic_test', $params);
    }

    public function goToPrevious()
    {
        $providerVariable = $this->testEntity->getAction() . 'Provider';

        if (!$this->$providerVariable->goToPrevious()) {
            $this->setAction(
                $this->getPreviousAction(
                    $this->testEntity->getAction()
                )
            );
        }

        $this->save();
    }
}