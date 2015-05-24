<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="client_base")
 */
class ClientBase
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $age;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\NotBlank()
     */
    protected $gender;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank()
     */
    protected $language;

    /**
     * @param mixed $age
     *
     * @return ClientBase
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $gender
     *
     * @return ClientBase
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $language
     *
     * @return ClientBase
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
