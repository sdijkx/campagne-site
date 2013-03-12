<?php
namespace GLZeist\Bundle\ProgrammaBundle\Listener;
use GLZeist\Bundle\ProgrammaBundle\Entity\Item;
use Doctrine\ORM\Event\LifecycleEventArgs;


class UploadListener
{
    private $rootDir;
    
    public function __construct($rootDir=null)
    {
        $this->rootDir=$rootDir;
    }
    
    
    public function preUpload(LifecycleEventArgs $args)
    {
        $entity=$this->supports($args);
        if($entity)
        {
            $file=$entity->getFile();
            if (null !== $file) {
                // do whatever you want to generate a unique name
                $filename = uniqid(mt_rand(), true);
                $entity->setImagefile($filename.'.jpg');
                $entity->setThumbfile($filename.'_thumb.jpg');
            }
        }
    }
    
    public function upload(LifecycleEventArgs $args)
    {
        $entity=$this->supports($args);
        if($entity)
        {
            $file=$entity->getFile();
            if (null === $file) {
                return;
            }
            $scaler=new ImageScaler();
            $filename=$file->getRealPath();
            
            $type=$scaler->getType($filename);

            $dest=$this->getRootDir().DIRECTORY_SEPARATOR.$entity->getImagefile();
            $destThumb=$this->getRootDir().DIRECTORY_SEPARATOR.$entity->getThumbfile();
            
            $scaler->scale($filename, $dest, $type, 300, 230);
            $scaler->scale($filename, $destThumb, $type, 120, 92);
            
        }        
    }
    
    public function removeUpload(LifecycleEventArgs $args)
    {
        $entity=$this->supports($args);
        if($entity)
        {
            $filename=$entity->getImagefile();
            if (null !== $filename) {
                unlink($this->rootDir.DIRECTORY_SEPARATOR.$filename);
            }
            
            
            $filename=$entity->getThumbfile();
            if (null !== $filename) {
                unlink($this->rootDir.DIRECTORY_SEPARATOR.$filename);
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
    
    private function getRootDir()
    {
        return $this->rootDir;
    }
    
    
    public function getSubscribedEvents() {
    return array(
        Events::prePersist,
        Events::preUpdate,
        Events::postPersist,
        Events::postUpdate,
        Events::postRemove
    );
}
   
    

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->preUpload($args);
    }
    
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->upload($args);
    }
    
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->preUpload($args);
    }
    
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->upload($args);
    }
    
    public function postRemove(LifecycleEventArgs $args)
    {
        $this->removeUpload($args);
    }
    
}
