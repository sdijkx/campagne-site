<?php

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
     * @ORM\OneToMany(targetEntity="Paragraaf",cascade={"all"},mappedBy="hoofdstuk")
     */
    private $paragrafen;
    
    
    public function __construct()
    {
        $this->paragrafen=new \Doctrine\Common\Collections\ArrayCollection();
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
