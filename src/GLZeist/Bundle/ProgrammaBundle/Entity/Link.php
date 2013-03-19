<?php

namespace GLZeist\Bundle\ProgrammaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Link
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
     *
     * @ORM\Column(name="titel", type="string", length=255)
     */
    private $titel;
    
    /**
     *
     * @ORM\Column(name="url", type="text")
     */    
    private $url;
    
    /**
     * @ORM\ManyToOne(targetEntity="Item",inversedBy="links")
     */
    private $item;
    
    
    public function __construct()
    {
    }
    
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitel() {
        return $this->titel;
    }

    public function setTitel($titel) {
        $this->titel = $titel;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getItem() {
        return $this->item;
    }

    public function setItem($item) {
        $this->item = $item;
    }


}