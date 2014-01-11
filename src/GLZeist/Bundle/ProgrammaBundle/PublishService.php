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
use GLZeist\Bundle\ProgrammaBundle\Entity;

class PublishService {
    
    private $em;
    private $logger;
    private $mailer;
    private $templating;
    private $afbeeldingenService;
    
    public function __construct(\Doctrine\ORM\EntityManager $em, \Symfony\Component\HttpKernel\Log\LoggerInterface $logger, \Swift_Mailer $mailer,$templating,$afbeeldingenService)
    {
        $this->em=$em;
        $this->mailer=$mailer;
        $this->logger=$logger;
        $this->templating=$templating;
        $this->afbeeldingenService=$afbeeldingenService;
        
    }
    
    public function unpublish(Entity\Item $item)
    {
        $this->em->beginTransaction();
        
        try
        {
            $publishedItem=$item->getPublishedItem();
            $item->setPublishedItem(null);
            $publishedItem->setItem(null);
            
            $this->em->persist($item);
            $this->em->persist($publishedItem);
            $this->em->remove($publishedItem);
            $this->em->flush();
            
            $this->em->commit();
            
            
        }
        catch(\Exception $e)
        {
            $this->em->rollback();
            $this->logger->err("Er is een fout: {$e->getMessage()} opgetreden, item {$item->getSlug()} is niet verwijderd als publicatie");
            throw new \Exception('Er is een fout opgetreden, het item kan niet worden verwijderd als publicatie '.$e->getMessage());
        }
        
        
    }
    
    public function publish(Entity\Item $item)
    {
        $this->em->beginTransaction();
        
        try
        {

            $publishedItem=$item->getPublishedItem();
            if($publishedItem==null)
            {
                $publishedItem=new Entity\PublishedItem();
                $item->setPublishedItem($publishedItem);
                $publishedItem->setItem($item);
            }

            //copy 
            $publishedItem->setTitel($item->getTitel());
            $publishedItem->setTweet($item->getTweet());        
            $publishedItem->setHoofdtekst($item->getHoofdtekst());
            $publishedItem->setKernboodschap($item->getKernboodschap());
            $publishedItem->setVerantwoording($item->getVerantwoording());
            $publishedItem->setVoorstellen($item->getVoorstellen());
            $publishedItem->setVideo($item->getVideo());
            $publishedItem->setImagefile($item->getImagefile());
            $publishedItem->setThumbfile($item->getThumbfile());

            $publishedItem->setHoofdstuk($item->getHoofdstuk());
            $publishedItem->setThema($item->getThema());

            $publishedItem->getRelaties()->clear();
            foreach($item->getRelaties() as $relatie)
            {
                if($relatie->getPublishedItem()!=null)
                {
                    $publishedItem->getRelaties()->add($relatie->getPublishedItem());
                }
            }
            //remove the links
            foreach($publishedItem->getLinks() as $link)
            {
                $publishedItem->getLinks()->removeElement($link);
                $this->em->remove($link);
                $this->em->flush();
            }
            //copy the links from the item
            foreach($item->getLinks() as $link)
            {
                //copy link
                $copy=new Entity\Link();
                $copy->setTitel($link->getTitel());
                $copy->setUrl($link->getUrl());
                $copy->setPublishedItem($publishedItem);
                $publishedItem->getLinks()->add($copy);
            }

            //remove the afbeeldingen
            foreach($publishedItem->getAfbeeldingen() as $afbeelding)
            {
                $publishedItem->getAfbeeldingen()->removeElement($afbeelding);
                $this->em->remove($afbeelding);
                $this->em->flush();
            }
            
            //copy the afbeeldingen from the item
            $afbeeldingId=0;
            foreach($item->getAfbeeldingen() as $afbeelding)
            {
                //copy afbeelding met nieuwe bestanden en bestandsnamen.
                $copy=$this->afbeeldingenService->maakAfbeeldingKopieMetNieuweBestanden($afbeelding,'published-items/'.$item->getSlug().'-'.$afbeeldingId++);
                $copy->setPublishedItem($publishedItem);
                $publishedItem->getAfbeeldingen()->add($copy);
            }
            
            $publishedItem->getTrefwoorden()->clear();
            foreach($item->getTrefwoorden() as $trefwoord)
            {
                $publishedItem->getTrefwoorden()->add($trefwoord);
            }
            
            $publishedItem->setGepubliceerdOp(new \DateTime());
            $item->setAangevraagd(false);

            $this->em->persist($item);
            $this->em->persist($publishedItem);
            $this->em->flush();

            $this->em->commit();
            $this->logger->info("Item {$item->getSlug()} gepubliceerd");
        }
        catch(\Exception $e)
        {
            $this->em->rollback();
            $this->logger->err("Er is een fout: {$e->getMessage()} opgetreden, item {$item->getSlug()} is niet gepubliceerd");
            throw new \Exception('Er is een fout opgetreden, het item kan niet worden gepubliceerd '.$e->getMessage());
        }
    }
        
//        try
//        {
//            $this->sendIsPublished($item);
//        }
//        catch(\Exception $e)
//        {
//            $this->logger->err("Er is een fout: {$e->getMessage()} opgetreden, voor item {$item->getSlug()} is geen notificatie naar de eigenaar verzonden");
//            throw new \Exception('Er is een fout opgetreden, het item is gepubliceerd maar de eigenaar heeft geen bevestiging ontvangen');
//        }
//        
//    }
//    
//    private function sendIsPublished(Entity\Item $entity)
//    {
//        $message = \Swift_Message::newInstance()
//            ->setSubject('GLZeist item gepubliceerd')
//            ->setFrom('noreply@groenlinkszeist.nl')
//            ->setTo($entity->getGemaaktDoor()->getEmail())
//            ->setBody(
//                $this->templating->render(
//                    'GLZeistProgrammaBundle:Admin:Item/publicatie_bevestiging.email.txt.twig',
//                    array(
//                        'item' => $entity
//                        )
//                )
//            )
//        ;            
//        $this->mailer->send($message);
//        
//    }
    
    
    public function requestPublication(Entity\Item $entity)
    {
        $this->em->beginTransaction();
        try
        {
            $entity->setAangevraagd(true);
            $this->em->persist($entity);
            $this->em->flush();            
            $this->em->commit();

        }
        catch(\Exception $e)
        {
            $this->em->rollback();
            
            throw new \Exception('Er is een fout opgetreden, de aanvraag kan niet worden opgeslagen');
        }
        
//        try
//        {
//            $this->sendRequestToModerators($entity);
//        }
//        catch(\Exception $e)
//        {
//            throw new \Exception('Er is een fout opgetreden, de aanvraag is mogelijk niet verzonden naar de redacteuren');
//        }
        
    }
    
    private function sendRequestToModerators(Entity\Item $entity)
    {
        $moderators=$this->em->getRepository('GLZeistProgrammaBundle:User')->findByRole(array('ROLE_MODERATOR','ROLE_ADMIN'));
        foreach($moderators as $moderator)
        {
            $message = \Swift_Message::newInstance()
                ->setSubject('GLZeist aanvraag voor publicatie')
                ->setFrom('noreply@groenlinkszeist.nl')
                ->setTo($entity->getGemaaktDoor()->getEmail())
                ->setBody(
                    $this->templating->render(
                        'GLZeistProgrammaBundle:Admin:Item/publicatie_aanvraag.email.txt.twig',
                        array(
                            'sender' => $entity->getGemaaktDoor(),
                            'moderator' => $moderator->getUsername(),
                            'item' => $entity
                            )
                    )
                )
            ;            
            $this->mailer->send($message);
        }
        
    }
}
