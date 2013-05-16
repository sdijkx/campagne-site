<?php

namespace GLZeist\Bundle\ProgrammaBundle;
use Doctrine\ORM\EntityManager;


class FullTextSearchService 
{
    private $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em=$em;
    }
    
    public function getEntityManager()
    {
        return $this->em;
    }
    
    public function search($search,$limit=0)
    {
        $em=$this->getEntityManager();

        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        
        $rsm->addScalarResult('relevance', 'relevance','float');

        $rsm->addEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\Thema', 't','thema');            
        $rsm->addFieldResult('t', 'thema_id', 'id');
        $rsm->addFieldResult('t', 'thema_titel', 'titel');
        $rsm->addFieldResult('t', 'thema_slug', 'slug');
        $rsm->addFieldResult('t', 'thema_tekst', 'tekst');
        
                
        $rsm->addEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\Persoon', 'p','persoon');            
        $rsm->addFieldResult('p', 'persoon_id', 'id');
        $rsm->addFieldResult('p', 'naam', 'naam');
        $rsm->addFieldResult('p', 'functie', 'functie');
        $rsm->addFieldResult('p', 'persoon_slug', 'slug');
        $rsm->addFieldResult('p', 'persoon_thumbfile', 'thumbfile');

        $rsm->addEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\PublishedItem', 'i','item');
        $rsm->addJoinedEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\Thema', 'i_t','i','thema');

        $rsm->addFieldResult('i', 'item_id', 'id');
        $rsm->addFieldResult('i', 'titel', 'titel');
        $rsm->addFieldResult('i', 'tweet', 'tweet');
        $rsm->addFieldResult('i', 'kernboodschap', 'kernboodschap');
        $rsm->addFieldResult('i', 'thumbfile', 'thumbfile');
        $rsm->addFieldResult('i', 'gepubliceerdOp', 'gepubliceerdOp');
        $rsm->addFieldResult('i', 'item_slug', 'slug');
        $rsm->addFieldResult('i_t', 'item_thema_id', 'id');
        $rsm->addFieldResult('i_t', 'item_thema_slug', 'slug');
        

        $search=addslashes($search);
        $limit=intval($limit);

        $sql = 
            "SELECT 
                i.id as item_id,
                i.titel,
                i.tweet,
                i.kernboodschap, 
                i.gepubliceerdOp,
                i.thumbfile,
                i.slug as item_slug,
                i_t.id as item_thema_id,
                i_t.slug as item_thema_slug,
                t.id as thema_id,
                t.titel as thema_titel,
                t.tekst as thema_tekst,
                t.slug as thema_slug,
                p.id as persoon_id,
                p.naam,
                p.functie,
                p.slug as persoon_slug,
                p.thumbfile as persoon_thumbfile,
                MATCH(s.keywords) AGAINST('{$search}' IN BOOLEAN MODE) + MATCH(s.search_text) AGAINST('{$search}') AS relevance 
             FROM 
                item_search AS s 
                LEFT JOIN PublishedItem AS i ON s.object_id = i.id AND s.object_type='item'
                LEFT JOIN Thema AS i_t ON i_t.id=i.thema_id
                LEFT JOIN Thema AS t ON s.object_id=t.id AND s.object_type='thema'
                LEFT JOIN Persoon AS p ON s.object_id=p.id AND s.object_type='persoon'
                WHERE 
                    MATCH(s.search_text) AGAINST('{$search}' IN BOOLEAN MODE) OR
                    MATCH(s.keywords) AGAINST('{$search}' IN BOOLEAN MODE) 
                ORDER BY relevance DESC, gepubliceerdOp DESC ".
                ($limit>0?" LIMIT {$limit} ":" ");


        $query = $em->createNativeQuery($sql, $rsm);
        
        $results = $query->getResult();
        
        return $results;
        
    }
}
