<?php
namespace GLZeist\Bundle\ProgrammaBundle\Repository;

class SpeerpuntRepository extends \Doctrine\ORM\EntityRepository
{

    public function findRandomForHomepage($limit=4)
    {
        $em=$this->getEntityManager();
        
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();

        $rsm->addEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\Speerpunt', 's');            
        $rsm->addFieldResult('s', 'id', 'id');
        $rsm->addFieldResult('s', 'titel', 'titel');
        $rsm->addFieldResult('s', 'url', 'url');
        $rsm->addFieldResult('s', 'afbeelding', 'afbeelding');
        $rsm->addFieldResult('s', 'content', 'content');
        
        $query=$em->createNativeQuery("SELECT * FROM Speerpunt ORDER BY RAND() LIMIT 4", $rsm);
        
        $results = $query->getResult();
        
        return $results;
        
    }
}
