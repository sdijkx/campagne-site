<?php

namespace GLZeist\Bundle\ProgrammaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table()
 * @ORM\Entity
 */

class Site {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     *
     * @ORM\Column(type="string",nullable=true)
     */        
    private $afdeling;
    
    /**
     * @ORM\Column(name="imagefile", type="string",nullable=true)
     */
    private $banner;
    
    /**
     * @Assert\File(maxSize="6000000",mimeTypes={"image/gif","image/png","image/jpg","image/jpeg"})
     */
    public $file;    
    
     /**
     * @ORM\ManyToMany(targetEntity="PublishedItem")
     */
    private $items;
    
    
    public function __construct() 
    {
        $this->items=new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getAfdeling() {
        return $this->afdeling;
    }

    public function setAfdeling($afdeling) {
        $this->afdeling = $afdeling;
    }

    public function getBanner() {
        return $this->banner;
    }

    public function setBanner($banner) {
        $this->banner = $banner;
    }

    public function getFile() {
        return $this->file;
    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function getItems() {
        return $this->items;
    }

    public function setItems($items) {
        $this->items = $items;
    }



}
