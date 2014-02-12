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


class TrefwoordService 
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
    
    public function search($trefwoordId,$limit=0)
    {
        $em=$this->getEntityManager();

        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        

        $rsm->addEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\WijkParel', 'wp','wijkparel');            
        $rsm->addJoinedEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\Wijk', 'wp_w','wp','wijk');
        $rsm->addFieldResult('wp', 'wijkparel_id', 'id');
        $rsm->addFieldResult('wp', 'wijkparel_titel', 'titel');
        $rsm->addFieldResult('wp', 'wijkparel_slug', 'slug');
        $rsm->addFieldResult('wp', 'wijkparel_tekst', 'tekst');        
        $rsm->addFieldResult('wp_w', 'wijkparel_wijk_id', 'id');
        $rsm->addFieldResult('wp_w', 'wijkparel_wijk_slug', 'slug');        
        $rsm->addFieldResult('wp_w', 'wijkparel_wijk_wijk', 'wijk');        
        

        $rsm->addEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\Wijk', 'w','wijk');            
        $rsm->addFieldResult('w', 'wijk_id', 'id');
        $rsm->addFieldResult('w', 'wijk_wijk', 'wijk');
        $rsm->addFieldResult('w', 'wijk_slug', 'slug');
        $rsm->addFieldResult('w', 'wijk_tekst', 'tekst');        

        $rsm->addEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\PublishedItem', 'i','item');
        $rsm->addJoinedEntityResult('GLZeist\Bundle\ProgrammaBundle\Entity\Thema', 'i_th','i','thema');

        $rsm->addFieldResult('i', 'item_id', 'id');
        $rsm->addFieldResult('i', 'titel', 'titel');
        $rsm->addFieldResult('i', 'tweet', 'tweet');
        $rsm->addFieldResult('i', 'kernboodschap', 'kernboodschap');
        $rsm->addFieldResult('i', 'thumbfile', 'thumbfile');
        $rsm->addFieldResult('i', 'gepubliceerdOp', 'gepubliceerdOp');
        $rsm->addFieldResult('i', 'item_slug', 'slug');
        $rsm->addFieldResult('i_th', 'item_thema_id', 'id');
        $rsm->addFieldResult('i_th', 'item_thema_slug', 'slug');
        

        $trefwoordId=addslashes($trefwoordId);
        $limit=intval($limit);

        $sql = 
            "SELECT 
                t.id as trefwoord_id,
                t.trefwoord as trefwoord,
                i.id as item_id,
                i.titel,
                i.tweet,
                i.kernboodschap, 
                i.gepubliceerdOp,
                i.thumbfile,
                i.slug as item_slug,
                i_th.id as item_thema_id,
                i_th.slug as item_thema_slug,
                w.id as wijk_id,
                w.wijk as wijk_wijk,
                w.slug as wijk_slug,
                wp.id as wijkparel_id,
                wp.titel as wijkparel_titel,
                wp.slug as wijkparel_slug,
                wp_w.id as wijkparel_wijk_id,
                wp_w.slug as wijkparel_wijk_slug,
                wp_w.wijk as wijkparel_wijk_wijk
             FROM 
                Trefwoord AS t 
                LEFT JOIN publisheditem_trefwoord AS i_t ON i_t.trefwoord_id=t.id
                LEFT JOIN PublishedItem AS i ON i_t.publisheditem_id=i.id 
                LEFT JOIN Thema AS i_th ON i_th.id=i.thema_id

                LEFT JOIN wijk_trefwoord AS w_t ON w_t.trefwoord_id=t.id
                LEFT JOIN wijk AS w ON w_t.wijk_id=w.id
                
                LEFT JOIN wijkparel_trefwoord AS wp_t ON wp_t.trefwoord_id=t.id
                LEFT JOIN WijkParel AS wp ON wp.id=wp_t.wijkparel_id
                LEFT JOIN Wijk AS wp_w ON wp.wijk_id=wp_w.id
                WHERE 
                    t.id='{$trefwoordId}' ".
                ($limit>0?" LIMIT {$limit} ":" ");

        $query = $em->createNativeQuery($sql, $rsm);
        
        $results = $query->getResult();
        
        return $results;
        
    }
}
