<?php

namespace Alteis\Bundle\VersionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class VersionAppRepository extends EntityRepository
{  
    /**
     * Permet de retourner la dernière version selon la date de création
     * @return Version
     */
    public function getLastVersion()
    {

        $query = $this->createQueryBuilder('v')
                ->orderBy('v.id', 'DESC')
                ->setMaxResults(1);

        return $query->getQuery()->getOneOrNullResult();
    }
}