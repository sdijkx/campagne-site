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
use Doctrine\ORM\Query;

class PublishedItemRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllForHomepage()
    {
        return $this->findByHomepage(5);
    }
    
    public function findByHomepage($limit,$offset=0)
    {
        return $this->getEntityManager()
                ->createQuery("SELECT i FROM GLZeistProgrammaBundle:PublishedItem i ORDER BY i.gepubliceerdOp DESC")
                ->setMaxResults($limit)
                ->setFirstResult($offset)
                ->getResult();
    }
    
    public function countByHomePage()
    {
        return $this->getEntityManager()->createQuery("SELECT count(i) FROM GLZeistProgrammaBundle:PublishedItem i")
                ->getSingleScalarResult();
    }
    
    
    
    public function findByThema($thema,$limit,$offset=0)
    {
        return $this->getEntityManager()->createQuery("SELECT i FROM GLZeistProgrammaBundle:PublishedItem i  JOIN i.thema t  WHERE t.slug=:thema ORDER BY i.gepubliceerdOp DESC")
                ->setParameter('thema',$thema)
                ->setMaxResults($limit)
                ->setFirstResult($offset)
                ->getResult();
    }
    public function countByThema($thema)
    {
        return $this->getEntityManager()->createQuery("SELECT count(i) FROM GLZeistProgrammaBundle:PublishedItem i JOIN i.thema t  WHERE t.slug=:thema")
                ->setParameter('thema',$thema)
                ->getSingleScalarResult();
    }
    
    
    public function findAllForTrefwoord($trefwoord)
    {
        return $this->getEntityManager()->createQuery("SELECT i FROM GLZeistProgrammaBundle:PublishedItem i JOIN i.trefwoorden t  WHERE t=:trefwoord ORDER BY i.gepubliceerdOp DESC")
                ->setParameter('trefwoord',$trefwoord)
                ->getResult();
    }
    
    public function findByTrefwoord($trefwoord)
    {
        return $this->getEntityManager()->createQuery("SELECT i FROM GLZeistProgrammaBundle:PublishedItem i JOIN i.trefwoorden t  WHERE t.slug=:trefwoord ORDER BY i.gepubliceerdOp DESC")
                ->setParameter('trefwoord',$trefwoord)
                ->getResult();
    }
    public function countByTrefwoord($trefwoord)
    {
        return $this->getEntityManager()->createQuery("SELECT COUNT(i) FROM GLZeistProgrammaBundle:PublishedItem i JOIN i.trefwoorden t  WHERE t.slug=:trefwoord")
                ->setParameter('trefwoord',$trefwoord)
                ->getSingleScalarResult();
    }
}
