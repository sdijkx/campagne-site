<?php
namespace GLZeist\Bundle\ProgrammaBundle;
use GLZeist\Bundle\ProgrammaBundle\Entity;

class PublishService {
    
    private $em;
    private $logger;
    
    public function __construct(\Doctrine\ORM\EntityManager $em, \Symfony\Component\HttpKernel\Log\LoggerInterface $logger)
    {
        $this->em=$em;
        $this->logger=$logger;
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
            $publishedItem->setHomepage($item->getHomepage());
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
            throw new \Exception('Er is een fout opgetreden, het item kan niet worden gepubliceerd');
        }
    }
}
