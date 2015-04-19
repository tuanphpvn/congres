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
class OrganVoteRuleRepository extends EntityRepository
{
    protected $classname;

    public function getOrganTypeRightToVoteForTextGroup(OrganType $organType, TextGroup $textGroup)
    {
        $voteRightCount = $this-t>createQueryBuilder('ovr')
            ->select('COUNT(ovr)')
            ->leftJoin('ovr.concernedOrganType', 'organtypes')
            ->where('ovr.textGroup = :textGroup')
            ->andWhere('organtypes.id = :organType OR SIZE(organtypes) = 0')
            ->setParameter('organType', $organType->getId())
            ->setParameter('textGroup', $textGroup->getId())
            ->getQuery()->getSingleScalarResult();

        return !!$voteRightCount;
    }
}
