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

namespace GLZeist\Bundle\ProgrammaBundle\Listener;
use GLZeist\Bundle\ProgrammaBundle\Entity\Item;
use GLZeist\Bundle\ProgrammaBundle\Entity\PublishedItem;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Routing\RouterInterface;
use GLZeist\Bundle\ProgrammaBundle\Listener\URLShortener;


class PublishedItemSearchFieldsListener
{
    
    public function __construct()
    {
    }
    
    private function supports(LifecycleEventArgs $args)
    {
        
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();    
        if($entity instanceof PublishedItem)
        {
            return $entity;
        }
        return false;
    }
    
    public function getSubscribedEvents() {
        return array(
            Events::prePersist,
            Events::preUpdate
        );
    }
    
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity=$this->supports($args);
        if($entity)
        {
            
            $updateTime=false;

            $keywords='';

            foreach($entity->getTrefwoorden() as $trefwoord)
            {
                $keywords.=' '.$trefwoord->getZoekterm();
            }
            $entity->setZoektrefwoorden($keywords);
    
            if(
                    $args->hasChangedField('titel') ||
                    $args->hasChangedField('hoofdtekst') ||
                    $args->hasChangedField('kernboodschap') ||
                    $args->hasChangedField('verantwoording') ||
                    $args->hasChangedField('voorstellen')
                    )
            {
                $entity->setZoektekst(
                    strtolower($entity->getTitel()).' '.
                    strtolower($entity->getHoofdtekst()).' '.                       
                    strtolower($entity->getKernboodschap()).' '.                       
                    strtolower($entity->getVerantwoording()).' '.                                               
                    strtolower($entity->getVoorstellen())  
                );
                $updateTime=true;
            }
            
            if($updateTime)
            {
                $entity->setGepubliceerdOp(new \DateTime());
            }
            
        }
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity=$this->supports($args);
        if($entity)
        {
            $keywords='';
            foreach($entity->getTrefwoorden() as $trefwoord)
            {
                $keywords.=' '.$trefwoord->getZoekterm();
            }
            $entity->setZoektrefwoorden($keywords);
            $entity->setZoektekst(
                strtolower($entity->getTitel()).' '.
                strtolower($entity->getHoofdtekst()).' '.                       
                strtolower($entity->getKernboodschap()).' '.                       
                strtolower($entity->getVerantwoording()).' '.                                               
                strtolower($entity->getVoorstellen())  
            );
            $entity->setGepubliceerdOp(new \DateTime());
        }
    }
        
}
