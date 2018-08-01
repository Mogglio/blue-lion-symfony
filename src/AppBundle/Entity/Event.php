<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 * @ORM\Table(name="event")
 */
class Event {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $description = null;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    public $date;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank()
     */
    public $start;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank()
     */
    public $end;

    public function getId() {
        return $this->id;
    }

    public function getName () {
        return $this->name;
    }

    public function getDescription () {

        return $this->description;
    }

    public function getDate() {
        return $this->date;
    }

    public function getStart () {
        return $this->start;
    }

    public function getEnd () {
        return $this->end;
    }

    public function setName ($name) {
        $this->name = $name;
    }

    public function setDescription ($description) {
        $this->description = $description;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function setStart ($start) {
        $this->start = $start;
    }

    public function setEnd ($end) {
        $this->end = $end;
    }

}
