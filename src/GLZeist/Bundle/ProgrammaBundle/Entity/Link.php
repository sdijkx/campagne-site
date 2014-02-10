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

/**
 * @ORM\Entity
 */
class Link
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
     *
     * @ORM\Column(name="titel", type="string", length=255)
     */
    private $titel;
    
    /**
     *
     * @ORM\Column(name="url", type="text")
     */    
    private $url;
    
    /**
     * @ORM\ManyToOne(targetEntity="Item",inversedBy="links")
     */
    private $item;

    /**
     * @ORM\ManyToOne(targetEntity="PublishedItem",inversedBy="links")
     */
    private $publishedItem;
    
        
    /**
     * @ORM\ManyToOne(targetEntity="Wijk",inversedBy="links")
     */
    private $wijk;
    
    /**
     * @ORM\ManyToOne(targetEntity="WijkParel",inversedBy="links")
     */
    private $wijkParel;
    
    
    
    public function __construct()
    {
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

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
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