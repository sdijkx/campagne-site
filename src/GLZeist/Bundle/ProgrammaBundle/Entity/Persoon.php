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
use GLZeist\Bundle\ProgrammaBundle\Searchable;
use GLZeist\Bundle\ProgrammaBundle\Annotation as App;

/**
 * Persoon
 *
 * @ORM\Entity
 */

class Persoon
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     *
     * @ORM\Column(type="string",nullable=true)
     */        
    private $naam;

    /**
     *
     * @ORM\Column(type="string",nullable=true)
     */    
    
    private $functie;

    /**
     *
     * @ORM\Column(type="text",nullable=true)
     */    
    private $personalia;
    
    /**
     * @Gedmo\Slug(fields={"naam"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;    
    
    /**
     * @ORM\Column(name="imagefile", type="string",nullable=true)
     */
    private $imagefile;
    
    /**
     * @ORM\Column(name="thumbfile", type="string",nullable=true)
     */
    private $thumbfile;    
    
    /**
     * @Assert\File(maxSize="6000000",mimeTypes={"image/gif","image/png","image/jpg","image/jpeg"})
     * @App\Image(width=300,height=280,filenameProperty="imagefile")
     * @App\Image(width=120,height=92,filenameProperty="thumbfile")
     */
    public $file;
    
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNaam() {
        return $this->naam;
    }

    public function setNaam($naam) {
        $this->naam = $naam;
    }

    public function getFunctie() {
        return $this->functie;
    }

    public function setFunctie($functie) {
        $this->functie = $functie;
    }
    public function getPersonalia() {
        return $this->personalia;
    }

    public function setPersonalia($personalia) {
        $this->personalia = $personalia;
    }
    
    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
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

    public function __toString()
    {
        return $this->naam;
    }    

    
    
}
