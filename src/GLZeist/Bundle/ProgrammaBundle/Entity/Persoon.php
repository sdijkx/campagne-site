<?php
namespace GLZeist\Bundle\ProgrammaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use GLZeist\Bundle\ProgrammaBundle\Searchable;

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
