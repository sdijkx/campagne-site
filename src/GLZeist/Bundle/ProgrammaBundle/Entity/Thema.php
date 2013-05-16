<?php

namespace GLZeist\Bundle\ProgrammaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Thema
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
     * @Gedmo\Slug(fields={"titel"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;
    
        
    
    public function __construct()
    {
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
    
    public function getTekst() {
        return $this->tekst;
    }

    public function setTekst($tekst) {
        $this->tekst = $tekst;
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

        
            

    public function __toString()
    {
        return $this->titel;
    }


}
