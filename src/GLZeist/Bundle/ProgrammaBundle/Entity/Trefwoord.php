<?php

namespace GLZeist\Bundle\ProgrammaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trefwoord
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Trefwoord
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
     * @ORM\Column(name="trefwoord", type="string", length=255)
     */
    private $trefwoord;


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
     * Set trefwoord
     *
     * @param string $trefwoord
     * @return Trefwoord
     */
    public function setTrefwoord($trefwoord)
    {
        $this->trefwoord = $trefwoord;
    
        return $this;
    }

    /**
     * Get trefwoord
     *
     * @return string 
     */
    public function getTrefwoord()
    {
        return $this->trefwoord;
    }
    
    public function __toString()
    {
        return $this->trefwoord;
    }    
}
