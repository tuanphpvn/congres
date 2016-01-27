<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;


/**
 * PageRepository.
 */
class PageRepository  extends EntityRepository
{
    public function findActivePage() {

        return $this
            ->createQueryBuilder('p')
            ->select('p')
            ->where('p.isActive = 1')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findActivePageByCategory(Category $category) {

        return $this
            ->createQueryBuilder('p')
            ->select('p')
            ->where('p.isActive = 1')
            ->andWhere('p.category = :category')
            ->setParameters('category', $category)
            ->getQuery()
            ->getResult()
            ;
    }
}
