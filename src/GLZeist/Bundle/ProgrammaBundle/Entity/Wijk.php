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
class Wijk implements EntityMetAfbeeldingen
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
     * @var string
     *
     * @ORM\Column(name="metaDescription", type="string", length=255, nullable=true)
     */
    private $metaDescription;
    

    /**
     * @var string
     *
     * @ORM\Column(name="wijk", type="string", length=255)
     */
    private $wijk;
    
    /**
     * @ORM\Column(name="samenvatting", type="text",nullable=true)
     */
    private $samenvatting;    
    
    
    /**
     * @ORM\Column(name="tekst", type="text",nullable=true)
     */
    private $tekst;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=255,nullable=true)
     */
    private $video;
    
    
    /**
     * @ORM\OneToMany(targetEntity="WijkParel",mappedBy="wijk",cascade={"all"})
     */
    private $wijkParels;
    
    /**
     * @ORM\ManyToMany(targetEntity="Trefwoord")
     * @ORM\OrderBy({"trefwoord"="ASC"})
     */
    private $trefwoorden;
    
    /**
     * @ORM\OneToMany(targetEntity="Afbeelding",mappedBy="wijk",cascade={"all"})
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     * @App\ImageCollection(width=400,height=300,fileProperty="file",filenameProperty="imagefile",strategy="ratio")
     * @App\ImageCollection(width=120,height=92,fileProperty="file",filenameProperty="thumbfile") 
     */
    private $afbeeldingen;
    
    /**
     * @ORM\OneToMany(targetEntity="Link",mappedBy="wijk",cascade={"all"})
     */
    private $links;
    
    
    public function __construct() {
        $this->wijkParels=new \Doctrine\Common\Collections\ArrayCollection();
        $this->trefwoorden=new \Doctrine\Common\Collections\ArrayCollection();
        $this->afbeeldingen=new \Doctrine\Common\Collections\ArrayCollection();
        $this->links=new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * @Gedmo\Slug(fields={"wijk"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;    


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getWijk() {
        return $this->wijk;
    }

    public function setWijk($wijk) {
        $this->wijk = $wijk;
    }

        
    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }
    
    public function getTekst() {
        return $this->tekst;
    }

    public function setTekst($tekst) {
        $this->tekst = $tekst;
    }
    
    public function getVideo() {
        return $this->video;
    }

    public function setVideo($video) {
        $this->video = $video;
    }
    
    public function getWijkParels() {
        return $this->wijkParels;
    }

    public function setWijkParels($wijkParels) {
        $this->wijkParels = $wijkParels;
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

        
        
    public function __toString()
    {
        return $this->wijk;
    }    
    
    
    public function getImageFile() {
        return null;
    }
    
    public function getMetaDescription() {
        return $this->metaDescription;
    }

    public function setMetaDescription($metaDescription) {
        $this->metaDescription = $metaDescription;
    }

    public function getSamenvatting() {
        return $this->samenvatting;
    }

    public function setSamenvatting($samenvatting) {
        $this->samenvatting = $samenvatting;
    }
}
