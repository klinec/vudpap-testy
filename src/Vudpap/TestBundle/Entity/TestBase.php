<?php

namespace Vudpap\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="test_base")
 */
class TestBase
{
    const ACTION_FORM = 'form';
    const ACTION_QUESTION = 'question';
    const ACTION_RESULT = 'result';

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Unique ID for URL
     *
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=32, nullable=true)
     */
    protected $url;

    /**
     * Name of the test
     *
     * @var string
     *
     * @ORM\Column(name="test_name", type="string", length=255)
     */
    protected $testName;

    /**
     * Action to be rendered
     *
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=255)
     */
    protected $action;

    /**
     * InitPage data
     *
     * @var string
     *
     * @ORM\Column(name="init_page", type="text", nullable=true)
     */
    protected $initPage;

    /**
     * Current question ID
     *
     * @var string
     *
     * @ORM\Column(name="question", type="text", nullable=true)
     */
    protected $question;

    /**
     * Serialized result
     *
     * @var string
     *
     * @ORM\Column(name="result", type="text", nullable=true)
     */
    protected $result;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="date")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="date")
     */
    protected $updatedAt;

    /**
     * @param string $action
     *
     * @return TestBase
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $initPage
     *
     * @return TestBase
     */
    public function setInitPage($initPage)
    {
        $this->initPage = $initPage;

        return $this;
    }

    /**
     * @return string
     */
    public function getInitPage()
    {
        return $this->initPage;
    }

    /**
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param string $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return TestBase
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $question
     *
     * @return TestBase
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param string $testName
     *
     * @return TestBase
     */
    public function setTestName($testName)
    {
        $this->testName = $testName;

        return $this;
    }

    /**
     * @return string
     */
    public function getTestName()
    {
        return $this->testName;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return TestBase
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string $url
     *
     * @return TestBase
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
