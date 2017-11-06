<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="events")
 */
class Event
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
     * Many Subjects have One Section.
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="events")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    /**
     * @var Course
     * Many Subjects have One Section.
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="events")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * @var Course
     * Many Subjects have One Section.
     * @ORM\ManyToOne(targetEntity="Local", inversedBy="events")
     * @ORM\JoinColumn(name="local_id", referencedColumnName="id")
     */
    private $local;

    /**
     * @var DateTime
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime",  nullable=false)
     */
    private $startDate;

    /**
     * @var DateTime
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime",  nullable=false)
     */
    private $endDate;

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->startDate->format('d.m.Y H:i') . "-" . $this->endDate->format('d.m.Y H:i') . " " . $this->course . " " . $this->local;
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
     * @return Event
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param Course $course
     * @return Event
     */
    public function setCourse($course)
    {
        $this->course = $course;
        return $this;
    }

    /**
     * @return Course
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param Course $group
     * @return Event
     */
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return Course
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * @param Course $local
     * @return Event
     */
    public function setLocal($local)
    {
        $this->local = $local;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     * @return Event
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $endDate
     * @return Event
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

}