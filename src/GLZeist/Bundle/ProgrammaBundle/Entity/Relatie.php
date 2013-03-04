<?php

namespace GLZeist\Bundle\ProgrammaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Relatie
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Relatie
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
     * @ORM\Column(name="toelichting", type="text")
     */
    private $toelichting;
    
    /**
     * @ORM\ManyToOne(targetEntity="item")
     */
    private $item;
    
    /**
     * @ORM\ManyToOne(targetEntity="item")
     */
    private $verwantItem;


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
     * Set toelichting
     *
     * @param string $toelichting
     * @return Relatie
     */
    public function setToelichting($toelichting)
    {
        $this->toelichting = $toelichting;
    
        return $this;
    }

    /**
     * Get toelichting
     *
     * @return string 
     */
    public function getToelichting()
    {
        return $this->toelichting;
    }
    
    public function getItem() {
        return $this->item;
    }

    public function setItem($item) {
        $this->item = $item;
    }

    public function getVerwantItem() {
        return $this->verwantItem;
    }

    public function setVerwantItem($verwantItem) {
        $this->verwantItem = $verwantItem;
    }

    public function __toString()
    {
        return $this->item->getTitel().' '.$this->verwantItem->getTitel();
    }

}
