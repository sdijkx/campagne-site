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
 * Hoofdstuk
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Afbeelding
{
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(name="titel", type="string", length=255)
     */
    private $titel;
    
    
    /**
     * @ORM\Column(name="width", type="integer",nullable=true)
     */
     private $width;

    /**
     * @ORM\Column(name="height", type="integer",nullable=true)
     */
    private $height;
    
    /**
     * @ORM\Column(name="thumbWidth", type="integer",nullable=true)
     */
    private $thumbWidth;
    

    /**
     * @ORM\Column(name="thumbHeight", type="integer",nullable=true)
     */
    private $thumbHeight;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="imagefile", type="string",nullable=true)
     */
    private $imagefile;
    
    /**
     * @var string
     *
     * @ORM\Column(name="thumbfile", type="string",nullable=true)
     */
    private $thumbfile;
    
    
    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Item",inversedBy="afbeeldingen")
     */
    private $item;   
    
    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="PublishedItem",inversedBy="afbeeldingen")
     */
    private $publishedItem;   

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Hoofdstuk",inversedBy="afbeeldingen")
     */
    private $hoofdstuk;   
    

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Wijk",inversedBy="afbeeldingen")
     */
    private $wijk;   
    
    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="WijkParel",inversedBy="afbeeldingen")
     */
    private $wijkParel;   
    
    
    

    /**
     * @Assert\File(maxSize="6000000",mimeTypes={"image/gif","image/png","image/jpg","image/jpeg"})
     */
    private $file;
    
    
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

    public function getItem() {
        return $this->item;
    }

    public function setItem($item) {
        $this->item = $item;
    }

    public function getPublishedItem() {
        return $this->publishedItem;
    }

    public function setPublishedItem($publishedItem) {
        $this->publishedItem = $publishedItem;
    }
        
    public function getImagefile() {
        return $this->imagefile;
    }

    public function setImagefile($imagefile) {
        $this->imagefile = $imagefile;
    }

    public function getThumbfile() {
        return $this->thumbfile;
    }

    public function setThumbfile($thumbfile) {
        $this->thumbfile = $thumbfile;
    }

    public function getFile() {
        return $this->file;
    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function getHoofdstuk() {
        return $this->hoofdstuk;
    }

    public function setHoofdstuk($hoofdstuk) {
        $this->hoofdstuk = $hoofdstuk;
    }
    
    public function getWidth() {
        return $this->width;
    }

    public function setWidth($width) {
        $this->width = $width;
    }

    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function getThumbWidth() {
        return $this->thumbWidth;
    }

    public function setThumbWidth($thumbWidth) {
        $this->thumbWidth = $thumbWidth;
    }

    public function getThumbHeight() {
        return $this->thumbHeight;
    }

    public function setThumbHeight($thumbHeight) {
        $this->thumbHeight = $thumbHeight;
    }

    public function getWijk() {
        return $this->wijk;
    }

    public function setWijk($wijk) {
        $this->wijk = $wijk;
    }
    
    public function getWijkParel() {
        return $this->wijkParel;
    }

    public function setWijkParel($wijkParel) {
        $this->wijkParel = $wijkParel;
    }






}
