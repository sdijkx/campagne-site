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

namespace GLZeist\Bundle\ProgrammaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use GLZeist\Bundle\ProgrammaBundle\Annotation as App;

/**
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
    private $titel;
    
    /**
     *
     * @ORM\Column(type="string",nullable=true)
     */        
    private $ondertitel;
    
    
    /**
     * @ORM\Column(name="banner", type="string",nullable=true)
     */
    private $banner;
    
    /**
     * @Assert\File(maxSize="6000000",mimeTypes={"image/gif","image/png","image/jpg","image/jpeg"})
     * @App\Image(width=800,height=280,filenameProperty="banner")
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

    public function getTitel() {
        return $this->titel;
    }

    public function setTitel($titel) {
        $this->titel = $titel;
    }

    public function getOndertitel() {
        return $this->ondertitel;
    }

    public function setOndertitel($ondertitel) {
        $this->ondertitel = $ondertitel;
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
