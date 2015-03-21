<?php

namespace AppBundle\Entity\Text;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use AppBundle\Entity\Adherent;
use AppBundle\Entity\Text\TextGroup;

/**
 * ContributionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TextRepository extends EntityRepository
{
    protected $classname = 'AppBundle\Entity\Text\Text';

    public function findTextAndVoteByTextGroup(Adherent $author,TextGroup $textGroup)
    {

        $select = 'SELECT text entity, (
            SELECT COUNT(av) FROM AppBundle\Entity\Vote\IndividualTextVote av LEFT JOIN av.text tx WHERE av.author = :author AND tx.id = text.id
            ) hasVoted';
        $from = ' FROM ' . $this->classname . ' text';
        $join = ' LEFT JOIN text.textGroup tg';
        $where = ' WHERE tg.id = :tgid';

        foreach ($textGroup->getVoteRules() as $voteRule)
        {
            $vid = $voteRule->getId();
            $ivatable = 'iva' . $vid;
            $select .= ', ' . $ivatable .'.voteFor as voteFor' . $vid;
            $join .= ' LEFT JOIN text.individualVoteAgregations ' . $ivatable;
            $where .= ' AND ('. $ivatable . '.voteRule = :vr' . $vid . ')';
        }

        $textAndVoteDQL = $select . $from . $join . $where . ' GROUP BY entity'; 
        $textAndVote = $this->getEntityManager()->createQuery($textAndVoteDQL);
        $textAndVote->setParameter('tgid', $textGroup->getId());
        $textAndVote->setParameter('author', $author->getId());

        foreach ($textGroup->getVoteRules() as $voteRule)
        {
            $vid = $voteRule->getId();
            $textAndVote->setParameter('vr' . $vid, $vid);
        }

        return $textAndVote->execute();
    }

}
