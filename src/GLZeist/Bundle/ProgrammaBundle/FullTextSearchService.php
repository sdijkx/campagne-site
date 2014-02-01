<?php
/*
    This file is part of GroenLinks Zeist Campagnesite.

    GroenLinks Zeist Campagnesite is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    GroenLinks Zeist Campagnesite is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GroenLinks Zeist Campagnesite.  If not, see <http://www.gnu.org/licenses/>.
    
*/

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
        
        $rsm->addEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\Hoofdstuk', 'h','hoofdstuk');            
        $rsm->addFieldResult('h', 'hoofdstuk_id', 'id');
        $rsm->addFieldResult('h', 'hoofdstuk_titel', 'titel');
        $rsm->addFieldResult('h', 'hoofdstuk_slug', 'slug');
        $rsm->addFieldResult('h', 'hoofdstuk_tekst', 'tekst');
        $rsm->addFieldResult('h', 'hoofdstuk_samenvatting', 'samenvatting');
        
                
        $rsm->addEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\Kandidaat', 'k','kandidaat');            
        $rsm->addFieldResult('k', 'kandidaat_id', 'id');
        $rsm->addFieldResult('k', 'naam', 'naam');
        $rsm->addFieldResult('k', 'personalia', 'personalia');
        $rsm->addFieldResult('k', 'kandidaat_slug', 'slug');
        $rsm->addFieldResult('k', 'kandidaat_thumbfile', 'thumbfile');

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
                h.id as hoofdstuk_id,
                h.titel as hoofdstuk_titel,
                h.tekst as hoofdstuk_tekst,
                h.samenvatting as hoofdstuk_samenvatting,
                h.slug as hoofdstuk_slug,        
                k.id as kandidaat_id,
                k.naam,
                k.personalia,
                k.slug as kandidaat_slug,
                k.thumbfile as kandidaat_thumbfile,
                MATCH(s.keywords) AGAINST('{$search}' IN BOOLEAN MODE) + MATCH(s.search_text) AGAINST('{$search}') AS relevance 
             FROM 
                item_search AS s 
                LEFT JOIN PublishedItem AS i ON s.object_id = i.id AND s.object_type='item'
                LEFT JOIN Thema AS i_t ON i_t.id=i.thema_id
                LEFT JOIN Thema AS t ON s.object_id=t.id AND s.object_type='thema'
                LEFT JOIN Hoofdstuk AS h ON s.object_id=h.id AND s.object_type='hoofdstuk'
                LEFT JOIN Kandidaat AS k ON s.object_id=k.id AND s.object_type='kandidaat'
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
