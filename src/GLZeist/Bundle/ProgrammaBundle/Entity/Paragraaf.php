<?php

namespace GLZeist\Bundle\ProgrammaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Paragraaf
 *
 * @ORM\Table()
 * @ORM\Entity()
 * 
 */
class Paragraaf
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
     * @ORM\ManyToOne(targetEntity="Hoofdstuk",inversedBy="paragrafen")
     */
    private $hoofdstuk;
    
    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="paragraaf")
     */
    private $items;

        
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
    
    public function getTitel() {
        return $this->titel;
    }

    public function setTitel($titel) {
        $this->titel = $titel;
    }


    public function getHoofdstuk() {
        return $this->hoofdstuk;
    }

    public function setHoofdstuk($hoofdstuk) {
        $this->hoofdstuk = $hoofdstuk;
    }
    
    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

        
    public function getItems() {
        return $this->items;
    }

    public function setItems($items) {
        $this->items = $items;
    }

    public function __toString()
    {
        return $this->titel;
    }



}
