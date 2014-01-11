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
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class ImageUploadListener
{
    private $rootDir;
    private $reader;
    private $logger;
    
    public function __construct(Reader $reader,LoggerInterface $logger,$rootDir=null)
    {
        $this->rootDir=$rootDir;
        $this->reader=$reader;
        $this->logger=$logger;
    }
    
    
    public function preUpload(LifecycleEventArgs $args)
    {
        $conf=$this->getImageConfiguration($args);
        foreach($conf as $image)
        {
            $file=$image->getFile();
            if (null === $file) {
                continue;
            }
            $filename = uniqid(mt_rand(), true);
            $image->setFilename($filename.'.jpg');
        }
    }
    
    public function upload(LifecycleEventArgs $args)
    {
        $conf=$this->getImageConfiguration($args);
        
        foreach($conf as $image)
        {
            $file=$image->getFile();
            if (null === $file) {
                continue;
            }
            $scaler=new ImageScaler();
            $filename=$file->getRealPath();
            
            $type=$scaler->getType($filename);

            $dest=$this->getRootDir().DIRECTORY_SEPARATOR.$image->getFilename();
            
            $scaler->scale($filename, $dest, $type, $image->getWidth(), $image->getHeight());
            
        }
    }
    
    public function removeUpload(LifecycleEventArgs $args)
    {
        $conf=$this->getImageConfiguration($args);
        foreach($conf as $image)
        {
            $filename=$image->getFilename();
            if (null !== $filename) {
                @unlink($this->rootDir.DIRECTORY_SEPARATOR.$filename);
            }
            
        }
    }
    
    private function getImageConfiguration(LifecycleEventArgs $args)
    {
        $images=array();
        $object = new \ReflectionObject($args->getEntity());
        foreach($object->getProperties() as $property)
        {
            foreach ($this->reader->getPropertyAnnotations($property) as $annotation)
            {

                if ($annotation instanceof \GLZeist\Bundle\ProgrammaBundle\Annotation\Image)
                {
                    $images[]=new UploadImage($object,$args->getEntity(),$property,$annotation);
                }
            }
        }
        return $images;
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
            Events::postRemove,
            Events::preFlush
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
    
    public function preFlush(PreFlushEventArgs $args)
    {
        $uow=$args->getEntityManager()->getUnitOfWork();
        $map=$uow->getIdentityMap();
        //traverse identity map and mark the uploaded images for update 
        foreach($map as $entitySet)
        {
            foreach($entitySet as $entity)
            {
                $object = new \ReflectionObject($entity);
                foreach($object->getProperties() as $property)
                {
                    foreach ($this->reader->getPropertyAnnotations($property) as $annotation)
                    {
                        if ($annotation instanceof \GLZeist\Bundle\ProgrammaBundle\Annotation\Image)
                        {
                            $uploadImage=new UploadImage($object,$entity,$property,$annotation);
                            if($uploadImage->getFile() instanceof \Symfony\Component\HttpFoundation\File\UploadedFile)
                            {
                                $uploadImage->setFilename(uniqid());
                            }
                        }
                    }
                }
            }
        }
    }
    
    
}
