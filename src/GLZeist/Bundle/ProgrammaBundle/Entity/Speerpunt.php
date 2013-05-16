<?php
namespace GLZeist\Bundle\ProgrammaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GLZeist\Bundle\ProgrammaBundle\Repository\SpeerpuntRepository")
 */

class Speerpunt {

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
     * @ORM\Column(name="content", type="string", length=255)
     */        
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="afbeelding", type="string", length=255)
     */        
    private $afbeelding;
    
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */    
    private $url;
    
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

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getAfbeelding() {
        return $this->afbeelding;
    }

    public function setAfbeelding($afbeelding) {
        $this->afbeelding = $afbeelding;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }


}
