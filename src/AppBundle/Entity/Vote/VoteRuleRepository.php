<?php

namespace AppBundle\Entity\Vote;

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
class VoteRuleRepository extends EntityRepository
{
    protected $classname;

    public function getAdherentRightToVoteForTextGroup(Adherent $adherent,TextGroup $textGroup)
    {
        $voteRightCount = $this->createQueryBuilder('vr')
            ->select('COUNT(vr)')
            ->leftJoin('vr.concernedResponsability', 'vrresp')
            ->leftJoin('vrresp.adherentResponsabilities' , 'adhresp')
            ->where('vr.textGroup = :textGroup')
            ->andWhere('adhresp.adherent = :adherent OR SIZE(vr.concernedResponsability) = 0')
            ->setParameter('adherent', $adherent->getId())
            ->setParameter('textGroup', $textGroup->getId())
            ->getQuery()->getSingleScalarResult();

        return !!$voteRightCount;
    }

}
