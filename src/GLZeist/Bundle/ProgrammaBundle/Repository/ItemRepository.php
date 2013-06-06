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

class ItemRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllForUser(\GLZeist\Bundle\ProgrammaBundle\Entity\User $user)
    {
        return $this->getEntityManager()->createQuery("SELECT i FROM GLZeistProgrammaBundle:Item i WHERE i.gemaaktDoor=:user")
                ->setParameter('user', $user)
                ->getResult();
    }
    
    public function findByIdForUser($id,\GLZeist\Bundle\ProgrammaBundle\Entity\User $user)
    {
        if($user->getRole()=='ROLE_USER')
        {
            return $this->getEntityManager()->createQuery("SELECT i FROM GLZeistProgrammaBundle:Item i WHERE i.id=:id AND i.gemaaktDoor=:user")
                    ->setParameter('id', $id)
                    ->setParameter('user', $user)
                    ->getOneOrNullResult();
        }
        elseif($user->getRole()=='ROLE_MODERATOR' || $user->getRole()=='ROLE_ADMIN')
        {
            return $this->getEntityManager()->createQuery("SELECT i FROM GLZeistProgrammaBundle:Item i WHERE i.id=:id")
                    ->setParameter('id', $id)
                    ->getOneOrNullResult();            
        }
        return null;
    }
    

    
    public function findAllForTrefwoord($trefwoord)
    {
        return $this->getEntityManager()->createQuery("SELECT i FROM GLZeistProgrammaBundle:Item i JOIN i.trefwoorden t  WHERE t=:trefwoord ORDER BY i.gewijzigdOp DESC")
                ->setParameter('trefwoord',$trefwoord)
                ->getResult();
    }
}