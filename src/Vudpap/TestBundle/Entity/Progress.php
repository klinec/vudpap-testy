<?php

namespace Vudpap\TestBundle\Entity;


class Progress {
    /**
     * Current progress
     *
     * @var int
     */
    protected $current = 1;

    /**
     * Total steps in progress
     *
     * @var int
     */
    protected $total = 1;

    public function __construct($current = 1, $total = 1)
    {
        $this->setCurrent($current);
        $this->setTotal($total);
    }

    /**
     * @return int
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @param int $current
     * @return Progress
     */
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     * @return Progress
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }
}