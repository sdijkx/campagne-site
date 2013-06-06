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
