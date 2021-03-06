<?php

namespace AppBundle\Entity\Vote;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Organ\Organ;
use AppBundle\Entity\Adherent;
use AppBundle\Entity\Text\TextGroup;

/**
 * IndividualOrganTextVote
 * People voting in an individual manner through an organ.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Vote\IndividualOrganTextVoteRepository")
 */
class IndividualOrganTextVote
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
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Text\TextGroup")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $textGroup;

    /**
     * @var \stdClass
     *
     *  @ORM\OneToMany(targetEntity="IndividualOrganTextVoteAgregation",
     * cascade={"persist", "remove"},
     *  mappedBy="individualOrganTextVote")
     */
    private $textVoteAgregations;

    /**
     * @var int
     *
     * @ORM\Column(name="voteTotal", type="integer")
     */
    private $voteTotal;

    /**
     * @var int
     *
     * @ORM\Column(name="voteAbstention", type="integer")
     */
    private $voteAbstention;

    /**
     * @var int
     *
     * @ORM\Column(name="voteNotTakingPart", type="integer")
     */
    private $voteNotTakingPart;

    /**
     * @var int
     *
     * @ORM\Column(name="voteBlank", type="integer")
     */
    private $voteBlank;

    /**
     * @var date
     *
     * @ORM\Column(name="meetingDate", type="date")
     */
    private $meetingDate;

    /**
     * @var \stdClass
     *
     *  @ORM\ManyToOne(targetEntity="AppBundle\Entity\Organ\Organ", inversedBy="textVoteReports")
     */
    private $organ;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Adherent")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    public function __construct(Organ $organ, Adherent $author, TextGroup $textGroup)
    {
        $this->organ = $organ;
        $this->author = $author;
        $this->textGroup = $textGroup;
        $this->voteAbstention = 0;
        $this->voteNotTakingPart = 0;
        $this->voteTotal = 0;
        $this->voteBlank = 0;

        foreach ($textGroup->getTexts() as $text) {
            $this->textVoteAgregations[] = new IndividualOrganTextVoteAgregation($text, $textGroup, $this);
        }
    }

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
     * Set textVoteAgregations.
     *
     * @param \stdClass $textVoteAgregations
     *
     * @return IndividualOrganTextVote
     */
    public function setTextVoteAgregations($textVoteAgregations)
    {
        $this->textVoteAgregations = $textVoteAgregations;

        return $this;
    }

    /**
     * Get textVoteAgregations.
     *
     * @return \stdClass
     */
    public function getTextVoteAgregations()
    {
        return $this->textVoteAgregations;
    }

    /**
     * Set voteAbstention.
     *
     * @param int $voteAbstention
     *
     * @return IndividualOrganTextVote
     */
    public function setVoteAbstention($voteAbstention)
    {
        $this->voteAbstention = $voteAbstention;

        return $this;
    }

    /**
     * Get voteAbstention.
     *
     * @return int
     */
    public function getVoteAbstention()
    {
        return $this->voteAbstention;
    }

    /**
     * Set voteNotTakingPart.
     *
     * @param int $voteNotTakingPart
     *
     * @return IndividualOrganTextVote
     */
    public function setVoteNotTakingPart($voteNotTakingPart)
    {
        $this->voteNotTakingPart = $voteNotTakingPart;

        return $this;
    }

    /**
     * Get voteNotTakingPart.
     *
     * @return int
     */
    public function getVoteNotTakingPart()
    {
        return $this->voteNotTakingPart;
    }

    /**
     * Set organ.
     *
     * @param \stdClass $organ
     *
     * @return IndividualOrganTextVote
     */
    public function setOrgan($organ)
    {
        $this->organ = $organ;

        return $this;
    }

    /**
     * Get organ.
     *
     * @return \stdClass
     */
    public function getOrgan()
    {
        return $this->organ;
    }

    /**
     * Set author.
     *
     * @param \stdClass $author
     *
     * @return IndividualOrganTextVote
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return \stdClass
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set textGroup.
     *
     * @param \AppBundle\Entity\Text\TextGroup $textGroup
     *
     * @return IndividualOrganTextVote
     */
    public function setTextGroup(\AppBundle\Entity\Text\TextGroup $textGroup)
    {
        $this->textGroup = $textGroup;

        return $this;
    }

    /**
     * Get textGroup.
     *
     * @return \AppBundle\Entity\Text\TextGroup
     */
    public function getTextGroup()
    {
        return $this->textGroup;
    }

    /**
     * Add textVoteAgregations.
     *
     * @param \AppBundle\Entity\Vote\IndividualOrganTextVoteAgregation $textVoteAgregations
     *
     * @return IndividualOrganTextVote
     */
    public function addTextVoteAgregation(\AppBundle\Entity\Vote\IndividualOrganTextVoteAgregation $textVoteAgregations)
    {
        $this->textVoteAgregations[] = $textVoteAgregations;

        return $this;
    }

    /**
     * Remove textVoteAgregations.
     *
     * @param \AppBundle\Entity\Vote\IndividualOrganTextVoteAgregation $textVoteAgregations
     */
    public function removeTextVoteAgregation(\AppBundle\Entity\Vote\IndividualOrganTextVoteAgregation $textVoteAgregations)
    {
        $this->textVoteAgregations->removeElement($textVoteAgregations);
    }

    /**
     * Set voteTotal.
     *
     * @param int $voteTotal
     *
     * @return IndividualOrganTextVote
     */
    public function setVoteTotal($voteTotal)
    {
        $this->voteTotal = $voteTotal;

        return $this;
    }

    /**
     * Get voteTotal.
     *
     * @return int
     */
    public function getVoteTotal()
    {
        return $this->voteTotal;
    }

    /**
     * Set voteBlank.
     *
     * @param int $voteBlank
     *
     * @return IndividualOrganTextVote
     */
    public function setVoteBlank($voteBlank)
    {
        $this->voteBlank = $voteBlank;

        return $this;
    }

    /**
     * Get voteBlank.
     *
     * @return int
     */
    public function getVoteBlank()
    {
        return $this->voteBlank;
    }

    /**
     * Set meetingDate.
     *
     * @param \DateTime $meetingDate
     *
     * @return IndividualOrganTextVote
     */
    public function setMeetingDate($meetingDate)
    {
        $this->meetingDate = $meetingDate;

        return $this;
    }

    /**
     * Get meetingDate.
     *
     * @return \DateTime
     */
    public function getMeetingDate()
    {
        return $this->meetingDate;
    }

    // FIXME : TO BE REMOVE AFTER PLATFORM VOTE EXPORT
    //
    public function getTextVoteResult()
    {
        $exportVote = array();
        foreach ($this->textVoteAgregations as $key => $val) {
            $exportVote[] = $val->__tostring();
        }

        return implode(' , ', $exportVote);
    }
}
