<?php
namespace GLZeist\Bundle\ProgrammaBundle\Repository;

class ItemRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllForHomepage()
    {
        return $this->getEntityManager()->createQuery("SELECT i FROM GLZeistProgrammaBundle:Item i WHERE i.homepage=true")->getResult();
    }
    
    public function findAllForSearch($search,$limit=0)
    {
            $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
            $rsm->addEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\Item', 'i');
            $rsm->addFieldResult('i', 'id', 'id');
            $rsm->addFieldResult('i', 'titel', 'titel');
            $rsm->addFieldResult('i', 'tweet', 'tweet');
            $rsm->addFieldResult('i', 'basistekst', 'basistekst');
            $rsm->addFieldResult('i', 'slug', 'slug');
            $rsm->addScalarResult('relevance', 'relevance','float');
            
            $search=mysql_real_escape_string($search);
            $limit=intval($limit);

            $sql = 
                "SELECT 
                    i.id,
                    i.titel,
                    i.tweet,
                    i.basistekst, 
                    i.slug,
                    MATCH(s.search_text) AGAINST('{$search}') AS relevance 
                 FROM 
                    item_search AS s JOIN Item AS i ON s.item_id = i.id  
                 WHERE MATCH(s.search_text) AGAINST('{$search}' IN BOOLEAN MODE)".
                 ($limit>0?" LIMIT {$limit} ":" ").
                 "HAVING relevance>0
                 ORDER BY relevance DESC";

            $query = $this->_em->createNativeQuery($sql, $rsm);

            $results = $query->getResult();

            return $results;
        
    }
    
    public function findAllRelated($id)
    {
        $this->getEntityManager()->createQuery(
                "SELECT r 
                 FROM GLZeistProgrammaBundle:Relatie r 
                 WHERE r.item=:id OR r.verwantItem=:id"
                )
                ->setParameter("id", $id)
                ->getResult();
    }
    
}