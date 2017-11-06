<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="groups")
 */
class Group
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="integer",  nullable=false)
     */
    private $number;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     */
    private $name;

    /**
     * Many Subjects have One Section.
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="groups")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    /**
     * @ORM\Column(type="string",  nullable=true)
     */
    private $extra;

    /**
     * One Course has Many Events.
     * @ORM\OneToMany(targetEntity="Event", mappedBy="group")
     * @ORM\OrderBy({"startDate" = "ASC"})
     */
    private $events;

    /**
     * Many Exams have Many Subjects.
     * @ORM\ManyToMany(targetEntity="User", inversedBy="groups")
     * @ORM\JoinTable(name="groups_users")
     */
    private $users;

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Group
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     * @return Group
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Group
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param mixed $extra
     * @return Group
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param mixed $events
     * @return Group
     */
    public function setEvents($events)
    {
        $this->events = $events;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param mixed $course
     * @return Group
     */
    public function setCourse($course)
    {
        $this->course = $course;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     * @return Group
     */
    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }


}