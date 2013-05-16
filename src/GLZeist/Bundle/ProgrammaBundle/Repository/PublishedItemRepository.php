<?php
namespace GLZeist\Bundle\ProgrammaBundle\Repository;

class PublishedItemRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllForHomepage()
    {
        return $this->getEntityManager()->createQuery("SELECT i FROM GLZeistProgrammaBundle:PublishedItem i ORDER BY i.gepubliceerdOp DESC")->setMaxResults(5)->getResult();
    }
    
    public function findAllForTrefwoord($trefwoord)
    {
        return $this->getEntityManager()->createQuery("SELECT i FROM GLZeistProgrammaBundle:PublishedItem i JOIN i.trefwoorden t  WHERE t=:trefwoord ORDER BY i.gepubliceerdOp DESC")
                ->setParameter('trefwoord',$trefwoord)
                ->getResult();
    }
}
