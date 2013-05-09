<?php

namespace GLZeist\Bundle\ProgrammaBundle\Repository;

class ItemRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllForUser(\GLZeist\Bundle\ProgrammaBundle\Entity\User $user)
    {
        return $this->getEntityManager()->createQuery("SELECT i FROM GLZeistProgrammaBundle:Item i WHERE i.gemaaktDoor=:user")
                ->setParameter('user', $user)
                ->getResult();
    }

    
    public function findAllForTrefwoord($trefwoord)
    {
        return $this->getEntityManager()->createQuery("SELECT i FROM GLZeistProgrammaBundle:Item i JOIN i.trefwoorden t  WHERE t=:trefwoord ORDER BY i.gewijzigdOp DESC")
                ->setParameter('trefwoord',$trefwoord)
                ->getResult();
    }
}