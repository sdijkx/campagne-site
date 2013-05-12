<?php
namespace GLZeist\Bundle\ProgrammaBundle\Repository;

class PublishedItemRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllForHomepage()
    {
        return $this->getEntityManager()->createQuery("SELECT i FROM GLZeistProgrammaBundle:PublishedItem i WHERE i.homepage=true")->getResult();
    }
    
    public function findAllForTrefwoord($trefwoord)
    {
        return $this->getEntityManager()->createQuery("SELECT i FROM GLZeistProgrammaBundle:PublishedItem i JOIN i.trefwoorden t  WHERE t=:trefwoord ORDER BY i.gewijzigdOp DESC")
                ->setParameter('trefwoord',$trefwoord)
                ->getResult();
    }
}
