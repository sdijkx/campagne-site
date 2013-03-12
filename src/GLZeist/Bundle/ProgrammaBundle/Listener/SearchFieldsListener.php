<?php
namespace GLZeist\Bundle\ProgrammaBundle\Listener;
use GLZeist\Bundle\ProgrammaBundle\Entity\Item;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Routing\RouterInterface;
use GLZeist\Bundle\ProgrammaBundle\Listener\URLShortener;


class SearchFieldsListener
{
    
    public function __construct()
    {
    }
    
    
    private function createSearchFields(PreUpdateEventArgs $args)
    {
        $entity=$this->supports($args);
        if($entity)
        {
            if($args->hasChangedField('trefwoorden'))
            {
                $keywords='';
                
                foreach($entity->getTrefwoorden() as $trefwoord)
                {
                    $keywords.=' '.$trefwoord.getZoekterm();
                }
                $entity->setZoektrefwoorden($keywords);
            }
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
            }
        }
    }
    
    private function supports(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();    
        if($entity instanceof Item)
        {
            return $entity;
        }
        
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
            if($args->hasChangedField('trefwoorden'))
            {
                $keywords='';
                
                foreach($entity->getTrefwoorden() as $trefwoord)
                {
                    $keywords.=' '.$trefwoord->getZoekterm();
                }
                $entity->setZoektrefwoorden($keywords);
                $updateTime=true;
            }
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
                $entity->setDatumtijd(new \DateTime());
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
            $entity->setDatumtijd(new \DateTime());
        }
    }
        
}
