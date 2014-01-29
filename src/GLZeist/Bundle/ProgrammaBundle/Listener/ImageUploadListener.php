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
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class ImageUploadListener
{
    private $rootDir;
    private $reader;
    private $logger;
    private $imageEntities=array();
    
    public function __construct(Reader $reader,LoggerInterface $logger,$rootDir=null)
    {
        $this->rootDir=$rootDir;
        $this->reader=$reader;
        $this->logger=$logger;
    }
    
    
    public function preUpload(LifecycleEventArgs $args)
    {
        $em=$args->getEntityManager();
        $uow=$args->getEntityManager()->getUnitOfWork();
        $conf=$this->getImageConfiguration($args);
        $this->logger->debug("preUpload ",array(get_class($args->getEntity())));
        foreach($conf as $image)
        {
            if(!$this->checkEntity($args->getEntity(), $image)) {
                continue;
            }
            
            $filename = uniqid(mt_rand(), true);
            $image->setFilename($filename.'.jpg');
            $this->logger->debug('pre upload set filename ',array(get_class($image->getInstance()), $image->getFilenamePropertyName(), $filename));
        }
    }
    
    public function upload(LifecycleEventArgs $args)
    {
        $conf=$this->getImageConfiguration($args);
        
        foreach($conf as $image)
        {
            
            if(!$this->checkEntity($args->getEntity(), $image)) {
                continue;
            }
            
            $file=$image->getFile();
            $scaler=new ImageScaler();
            $filename=$file->getRealPath();
            
            $type=$scaler->getType($filename);

            $dest=$this->getRootDir().DIRECTORY_SEPARATOR.$image->getFilename();
            
            $scaler->scale($filename, $dest, $type, $image->getWidth(), $image->getHeight());
            $this->logger->debug('Scale image ',array($filename, $dest));
        }
    }
    
    private function checkEntity($entity,$uploadImage) {
        if($entity !== $uploadImage->getInstance()) {
            $this->logger->debug("Entity is ongelijk aan ImageUpload Instance");
            return false;
        }

        $file=$uploadImage->getFile();
        if (null === $file) {
            return false;;
        }        
        return true;
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
        return $this->imageEntities;
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
        $this->logger->debug('Pre Persist',array(get_class($args->getEntity())));
        //$this->createImageEntityMap($args->getEntityManager());
        $this->preUpload($args);
    }
    
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->logger->debug('Post Persist',array(get_class($args->getEntity())));
        $this->upload($args);
    }
    
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $this->logger->debug('Pre Update',array(get_class($args->getEntity())));    
        $this->preUpload($args);
    }
    
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->logger->debug('Post Update',array(get_class($args->getEntity())));        
        $this->upload($args);
    }
    
    public function postRemove(LifecycleEventArgs $args)
    {
        $this->logger->debug('Post Remove',array(get_class($args->getEntity())));        
        $this->removeUpload($args);
    }
    
    public function preFlush(PreFlushEventArgs $args) {
        $this->createImageEntityMap($args->getEntityManager());        
    } 
    
    
    
    private function createImageEntityMap($em) {
        $this->logger->debug('Create Image Entity Map');        
        $uow=$em->getUnitOfWork();
        $map=$uow->getIdentityMap();
        //traverse identity map and mark the uploaded images for update 
        foreach($map as $mapKey => $entitySet)
        {
            foreach($entitySet as $key => $entity)
            {
                $object = new \ReflectionObject($entity);
                foreach($object->getProperties() as $property)
                {
                    foreach ($this->reader->getPropertyAnnotations($property) as $annotation)
                    {
                        if ($annotation instanceof \GLZeist\Bundle\ProgrammaBundle\Annotation\Image)
                        {
                            $imageUploadId=$this->makeImageUploadId($property,$annotation);
                            if(!key_exists($imageUploadId, $this->imageEntities))
                            {
                                $this->addUploadImageToEntityMap($imageUploadId, UploadImage::createWithImage($object,$entity,$property,$annotation));
                            }
                        }
                        if ($annotation instanceof \GLZeist\Bundle\ProgrammaBundle\Annotation\ImageCollection)
                        {
                            $property->setAccessible(true);
                            $collection=$property->getValue($entity);
                            foreach($collection as $id => $afbeelding) {
                                $uploadImageId=$this->makeImageUploadCollectionId($property, $annotation, $id);
                                if(!key_exists($uploadImageId, $this->imageEntities))
                                {
                                    $this->addUploadImageToEntityMap($uploadImageId, UploadImage::createWithImageCollection(new \ReflectionObject($afbeelding),$afbeelding,$annotation));
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->logger->debug('image entities count',array(count($this->imageEntities, COUNT_RECURSIVE)));        
    }
    
    private function makeImageUploadId($property,$annotation) {
        return $property->getName().'_'.$annotation->filenameProperty;
    }
    
    private function makeImageUploadCollectionId($property,$annotation,$id) {
        return $property->getName().'_'.$annotation->filenameProperty.'_'.$id;
    }
    
    
    private function addUploadImageToEntityMap($id,UploadImage $uploadImage) {
        if($uploadImage->getFile()!=null) {
            $this->imageEntities[$id]=$uploadImage;
            $this->assignUniqueIdToFilename($uploadImage);
            $this->logger->debug('add image entity',array($id, get_class($uploadImage->getInstance())));
        } 
    }
    
    private function assignUniqueIdToFilename(UploadImage $uploadImage) {
        if($uploadImage->getFile() instanceof \Symfony\Component\HttpFoundation\File\UploadedFile)
        {
            $uploadImage->setFilename(uniqid());
            
        }
    }
    
    
}
