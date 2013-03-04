<?php

namespace GLZeist\Bundle\ProgrammaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GLZeist\Bundle\ProgrammaBundle\Repository\ItemRepository")
 */
class Item
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
     * @ORM\Column(name="basistekst", type="text")
     */
    private $basistekst;

    /**
     * @var string
     *
     * @ORM\Column(name="hoofdtekst", type="text")
     */
    private $hoofdtekst;

    /**
     * @var string
     *
     * @ORM\Column(name="tweet", type="string", length=140)
     */
    private $tweet;

    /**
     * @var string
     *
     * @ORM\Column(name="verantwoording", type="text")
     */
    private $verantwoording;

    /**
     * @var string
     *
     * @ORM\Column(name="voorstellen", type="text")
     */
    private $voorstellen;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="homepage", type="boolean")
     */
    private $homepage;
    
    
    /**
     * @Gedmo\Slug(fields={"titel"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    
    /**
     * @ORM\ManyToMany(targetEntity="Trefwoord")
     */
    private $trefwoorden;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Media",mappedBy="item",cascade={"all"})
     */
    private $media;
    
    /**
     * @ORM\ManyToOne(targetEntity="Paragraaf",inversedBy="paragraaf",cascade={"all"})
     */
    private $paragraaf;
    
    
    
    public function __construct()
    {
        $this->trefwoorden=new \Doctrine\Common\Collections\ArrayCollection();
        $this->media=new \Doctrine\Common\Collections\ArrayCollection();
        $this->homepage=false;
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
     * @return Item
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

    /**
     * Set basistekst
     *
     * @param string $basistekst
     * @return Item
     */
    public function setBasistekst($basistekst)
    {
        $this->basistekst = $basistekst;
    
        return $this;
    }

    /**
     * Get basistekst
     *
     * @return string 
     */
    public function getBasistekst()
    {
        return $this->basistekst;
    }

    /**
     * Set hoofdtekst
     *
     * @param string $hoofdtekst
     * @return Item
     */
    public function setHoofdtekst($hoofdtekst)
    {
        $this->hoofdtekst = $hoofdtekst;
    
        return $this;
    }

    /**
     * Get hoofdtekst
     *
     * @return string 
     */
    public function getHoofdtekst()
    {
        return $this->hoofdtekst;
    }

    /**
     * Set tweet
     *
     * @param string $tweet
     * @return Item
     */
    public function setTweet($tweet)
    {
        $this->tweet = $tweet;
    
        return $this;
    }

    /**
     * Get tweet
     *
     * @return string 
     */
    public function getTweet()
    {
        return $this->tweet;
    }

    /**
     * Set verantwoording
     *
     * @param string $verantwoording
     * @return Item
     */
    public function setVerantwoording($verantwoording)
    {
        $this->verantwoording = $verantwoording;
    
        return $this;
    }

    /**
     * Get verantwoording
     *
     * @return string 
     */
    public function getVerantwoording()
    {
        return $this->verantwoording;
    }

    /**
     * Set voorstellen
     *
     * @param string $voorstellen
     * @return Item
     */
    public function setVoorstellen($voorstellen)
    {
        $this->voorstellen = $voorstellen;
    
        return $this;
    }

    /**
     * Get voorstellen
     *
     * @return string 
     */
    public function getVoorstellen()
    {
        return $this->voorstellen;
    }
    
    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

        
    public function getTrefwoorden() {
        return $this->trefwoorden;
    }

    public function setTrefwoorden($trefwoorden) {
        $this->trefwoorden = $trefwoorden;
    }

    public function getMedia() {
        return $this->media;
    }

    public function setMedia($media) {
        $this->media = $media;
    }

    public function getRelaties() {
        return $this->relaties;
    }

    public function setRelaties($relaties) {
        $this->relaties = $relaties;
    }
    
    public function getHomepage() {
        return $this->homepage;
    }

    public function setHomepage($homepage) {
        $this->homepage = $homepage;
    }
    
    public function getParagraaf() {
        return $this->paragraaf;
    }

    public function setParagraaf($paragraaf) {
        $this->paragraaf = $paragraaf;
    }

    
            
    public function __toString()
    {
        return $this->titel;
    }


}
