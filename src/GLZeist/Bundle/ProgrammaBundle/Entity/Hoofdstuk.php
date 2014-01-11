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
 * @ORM\Entity(repositoryClass="GLZeist\Bundle\ProgrammaBundle\Repository\HoofdstukRepository")
 */
class Hoofdstuk implements EntityMetAfbeeldingen
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
     * @var int
     *
     * @ORM\Column(name="volgnummer", type="integer")
     */
    private $volgnummer;

    /**
     * @var string
     *
     * @ORM\Column(name="titel", type="string", length=255)
     */
    private $titel;
    
    /**
     * @Gedmo\Slug(fields={"titel"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;
    
    /**
     * @var string
     *
     * @ORM\Column(name="metaDescription", type="string", length=255, nullable=true)
     */
    private $metaDescription;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tekst", type="text", nullable=true)
     */
    private $tekst;
    
    /**
     * @var string
     *
     * @ORM\Column(name="samenvatting", type="text", nullable=true)
     */
    private $samenvatting;
    
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
     * @Assert\File(maxSize="6000000",mimeTypes={"image/gif","image/png","image/jpg","image/jpeg"})
     * @App\Image(width=300,height=280,filenameProperty="imagefile")
     * @App\Image(width=120,height=92,filenameProperty="thumbfile")
     */
    private $file;
    
    
    /**
     *
     * @ORM\OneToMany(targetEntity="Afbeelding",mappedBy="hoofdstuk",cascade={"all"})
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $afbeeldingen;    
    
    
    
    public function __construct()
    {
        $this->afbeeldingen=new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    public function getVolgnummer() {
        return $this->volgnummer;
    }

    public function setVolgnummer($volgnummer) {
        $this->volgnummer = $volgnummer;
    }

    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titel
     *
     * @param string $titel
     * @return Hoofdstuk
     */
    public function setTitel($titel)
    {
        $this->titel = $titel;
    
        return $this;
    }

    /**
     * Get titel
     *
     * @return string 
     */
    public function getTitel()
    {
        return $this->titel;
    }
    
    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

    public function getMetaDescription() {
        return $this->metaDescription;
    }

    public function setMetaDescription($metaDescription) {
        $this->metaDescription = $metaDescription;
    }

    public function getTekst() {
        return $this->tekst;
    }

    public function setTekst($tekst) {
        $this->tekst = $tekst;
    }
    
    public function getSamenvatting() {
        return $this->samenvatting;
    }

    public function setSamenvatting($samenvatting) {
        $this->samenvatting = $samenvatting;
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

    public function getAfbeeldingen() {
        return $this->afbeeldingen;
    }

    public function setAfbeeldingen($afbeeldingen) {
        
        foreach($afbeeldingen as $afbeelding) {
            $afbeelding->setHoofdstuk($this);
        }
        $this->afbeeldingen = $afbeeldingen;
    }

            
    
    public function __toString()
    {
        return $this->titel;
    }


}
