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
    
    
    public function findAllForSearch($search,$limit=0)
    {
            $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
            $rsm->addEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\Item', 'i');
            $rsm->addJoinedEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\Thema', 't','i','thema');
            $rsm->addFieldResult('i', 'item_id', 'id');
            $rsm->addFieldResult('i', 'titel', 'titel');
            $rsm->addFieldResult('i', 'tweet', 'tweet');
            $rsm->addFieldResult('i', 'kernboodschap', 'kernboodschap');
            $rsm->addFieldResult('i', 'thumbfile', 'thumbfile');
            $rsm->addFieldResult('i', 'item_slug', 'slug');
            $rsm->addFieldResult('t', 'thema_id', 'id');
            $rsm->addFieldResult('t', 'thema_slug', 'slug');
            $rsm->addScalarResult('relevance', 'relevance','float');
            
            $search=addslashes($search);
            $limit=intval($limit);

            $sql = 
                "SELECT 
                    i.id as item_id,
                    i.titel,
                    i.tweet,
                    i.kernboodschap, 
                    i.thumbfile,
                    i.slug as item_slug,
                    t.id as thema_id,
                    t.slug as thema_slug,
                    MATCH(s.keywords) AGAINST('{$search}' IN BOOLEAN MODE) +
                    MATCH(s.search_text) AGAINST('{$search}') 
                    AS relevance 
                 FROM 
                    item_search AS s 
                    JOIN PublishedItem AS i ON s.item_id = i.id 
                    JOIN Thema AS t ON t.id=i.thema_id
                    WHERE 
                        MATCH(s.search_text) AGAINST('{$search}' IN BOOLEAN MODE) OR
                        MATCH(s.keywords) AGAINST('{$search}' IN BOOLEAN MODE) 
                    ORDER BY relevance DESC, gepubliceerdOp DESC ".
                    ($limit>0?" LIMIT {$limit} ":" ");
                    
                
            $query = $this->_em->createNativeQuery($sql, $rsm);
            
            $results = $query->getResult();

            return $results;
        
    }
    
}
