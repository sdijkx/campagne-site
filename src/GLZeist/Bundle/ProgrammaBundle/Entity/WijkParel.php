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
use GLZeist\Bundle\ProgrammaBundle\Annotation as App;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Wijk
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class WijkParel implements EntityMetAfbeeldingen {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Wijk")
     */
    private $wijk;
    
    
    /**
     * @ORM\Column(name="titel", type="string",length=255,nullable=true)
     */
    private $titel;
    
    /**
     * @Gedmo\Slug(fields={"titel"})
     * @ORM\Column(length=128)
     */
    private $slug;        
        
    /**
     * @ORM\Column(name="samenvatting", type="text",nullable=true)
     */
    private $samenvatting;

    /**
     * @ORM\Column(name="tekst", type="text",nullable=true)
     */
    private $tekst;
    
    /**
     * @ORM\Column(name="promo", type="text",nullable=true)
     */
    private $promo;
    
    
    
    /**
     * @ORM\ManyToMany(targetEntity="Trefwoord")
     * @ORM\OrderBy({"trefwoord"="ASC"})
     */
    private $trefwoorden;
    
    /**
     * @ORM\OneToMany(targetEntity="Afbeelding",mappedBy="wijkParel",cascade={"all"})
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     * @App\ImageCollection(width=400,height=300,fileProperty="file",filenameProperty="imagefile",strategy="ratio")
     * @App\ImageCollection(width=120,height=92,fileProperty="file",filenameProperty="thumbfile") 
     */
    private $afbeeldingen;    
    
    
    /**
     * @ORM\OneToMany(targetEntity="Link",mappedBy="wijkParel",cascade={"all"})
     */
    private $links;    
    
    
    public function __construct() {
        
    }
    
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getWijk() {
        return $this->wijk;
    }

    public function setWijk($wijk) {
        $this->wijk = $wijk;
    }
    
    public function getTitel() {
        return $this->titel;
    }

    public function setTitel($titel) {
        $this->titel = $titel;
    }
    
    public function getTekst() {
        return $this->tekst;
    }

    public function setTekst($tekst) {
        $this->tekst = $tekst;
    }
    
    public function getPromo() {
        return $this->promo;
    }

    public function setPromo($promo) {
        $this->promo = $promo;
    }

    

    public function getImageFile() {
        return null;
    }

    public function getTrefwoorden() {
        return $this->trefwoorden;
    }

    public function setTrefwoorden($trefwoorden) {
        $this->trefwoorden = $trefwoorden;
    }

    public function getAfbeeldingen() {
        return $this->afbeeldingen;
    }

    public function setAfbeeldingen($afbeeldingen) {
        $this->afbeeldingen = $afbeeldingen;
    }

    public function getLinks() {
        return $this->links;
    }

    public function setLinks($links) {
        $this->links = $links;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }


    public function getSamenvatting() {
        return $this->samenvatting;
    }

    public function setSamenvatting($samenvatting) {
        $this->samenvatting = $samenvatting;
    }




}
