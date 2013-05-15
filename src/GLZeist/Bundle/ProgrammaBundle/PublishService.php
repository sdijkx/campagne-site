<?php
namespace GLZeist\Bundle\ProgrammaBundle;
use GLZeist\Bundle\ProgrammaBundle\Entity;

class PublishService {
    
    private $em;
    private $logger;
    private $mailer;
    private $templating;
    
    public function __construct(\Doctrine\ORM\EntityManager $em, \Symfony\Component\HttpKernel\Log\LoggerInterface $logger, \Swift_Mailer $mailer,$templating)
    {
        $this->em=$em;
        $this->mailer=$mailer;
        $this->logger=$logger;
        $this->templating=$templating;
        
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

            $publishedItem->setThema($item->getThema());
            $publishedItem->setParagraaf($item->getParagraaf());

            $publishedItem->getRelaties()->clear();
            foreach($item->getRelaties() as $relatie)
            {
                if($relatie->getPublishedItem()!=null)
                {
                    $publishedItem->getRelaties()->add($relatie->getPublishedItem());
                }
            }

            $publishedItem->getLinks()->clear();
            foreach($item->getLinks() as $link)
            {
                //copy link
                $copy=new Entity\Link();
                $copy->setTitel($link->getTitel());
                $copy->setUrl($link->getUrl());
                $publishedItem->getLinks()->add($copy);
            }

            $publishedItem->getMedia()->clear();
            foreach($item->getMedia() as $media)
            {
                //copy link
                $copy=new Entity\Media();
                $copy->setTitel($media->getTitel());
                $copy->setUrl($media->getUrl());
                $copy->setPosition($media->getPosition());
                $publishedItem->getMedia()->add($link);
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
        
        try
        {
            $this->sendIsPublished($item);
        }
        catch(\Exception $e)
        {
            $this->logger->err("Er is een fout: {$e->getMessage()} opgetreden, voor item {$item->getSlug()} is geen notificatie naar de eigenaar verzonden");
            throw new \Exception('Er is een fout opgetreden, het item is gepubliceerd maar de eigenaar heeft geen bevestiging ontvangen');
        }
        
    }
    
    private function sendIsPublished(Entity\Item $entity)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('GLZeist item gepubliceerd')
            ->setFrom('noreply@groenlinkszeist.nl')
            ->setTo($entity->getGemaaktDoor()->getEmail())
            ->setBody(
                $this->templating->render(
                    'GLZeistProgrammaBundle:Admin:Item/publicatie_bevestiging.email.txt.twig',
                    array(
                        'item' => $entity
                        )
                )
            )
        ;            
        $this->mailer->send($message);
        
    }
    
    
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
        
        try
        {
            $this->sendRequestToModerators($entity);
        }
        catch(\Exception $e)
        {
            throw new \Exception('Er is een fout opgetreden, de aanvraag is mogelijk niet verzonden naar de redacteuren');
        }
        
    }
    
    private function sendRequestToModerators(Entity\Item $entity)
    {
        $moderators=$this->em->getRepository('GLZeistProgrammaBundle:User')->findByRole(array('ROLE_MODERATOR','ROLE_ADMIN'));
        foreach($moderators as $moderator)
        {
            $message = \Swift_Message::newInstance()
                ->setSubject('GLZeist aanvraag voor publicatie')
                ->setFrom('noreply@groenlinkszeist.nl')
                ->setTo($user->getEmail())
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
