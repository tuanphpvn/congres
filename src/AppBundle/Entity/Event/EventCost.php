<?php

namespace AppBundle\Entity\Event;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventCost.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Event\EventCostRepository")
 */
class EventCost
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="cost", type="float")
     */
    private $cost;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="costs")
     */
    private $event;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return EventCost
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set cost.
     *
     * @param int $cost
     *
     * @return EventCost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost.
     *
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set event.
     *
     * @param \AppBundle\Entity\Event\Event $event
     *
     * @return EventCost
     */
    public function setEvent(\AppBundle\Entity\Event\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event.
     *
     * @return \AppBundle\Entity\Event\Event
     */
    public function getEvent()
    {
        return $this->event;
    }
    public function __toString()
    {
        return $this->name.' ('.$this->cost.'€)';
    }
}
