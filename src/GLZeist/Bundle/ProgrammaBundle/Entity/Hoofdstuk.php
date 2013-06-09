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

/**
 * Hoofdstuk
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Hoofdstuk
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
     * @ORM\ManyToMany(targetEntity="Thema")
     */
    private $themas;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Paragraaf",cascade={"all"},mappedBy="hoofdstuk")
     */
    private $paragrafen;
    
    
    public function __construct()
    {
        $this->paragrafen=new \Doctrine\Common\Collections\ArrayCollection();
        $this->themas=new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getThemas() {
        return $this->themas;
    }

    public function setThemas($themas) {
        $this->themas = $themas;
    }

            
    public function getParagrafen() {
        return $this->paragrafen;
    }

    public function setParagrafen($paragrafen) {
        $this->paragrafen = $paragrafen;
    }

    
    
    public function __toString()
    {
        return $this->titel;
    }


}
