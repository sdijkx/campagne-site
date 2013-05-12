<?php

namespace GLZeist\Bundle\ProgrammaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GLZeist\Bundle\ProgrammaBundle\Repository\ItemRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(name="kernboodschap", type="text",nullable=true)
     */
    private $kernboodschap;

    /**
     * @var string
     *
     * @ORM\Column(name="hoofdtekst", type="text",nullable=true)
     */
    private $hoofdtekst;

    /**
     * @var string
     *
     * @Assert\Length(max=100)
     * @ORM\Column(name="tweet", type="string", length=100,nullable=true)
     */
    private $tweet;
    
    /**
     * @var string
     *
     * @ORM\Column(name="verantwoording", type="text",nullable=true)
     */
    private $verantwoording;

    /**
     * @var string
     *
     * @ORM\Column(name="voorstellen", type="text",nullable=true)
     */
    private $voorstellen;
        
        
    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string",nullable=true)
     */
    private $video;
        
    /**
     * @var string
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
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $gemaaktDoor;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $gemaaktOp;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $gewijzigdOp;
    
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $aangevraagd;
    
    
    /**
     * @ORM\ManyToMany(targetEntity="Trefwoord")
     * @ORM\OrderBy({"trefwoord"="DESC"})
     */
    private $trefwoorden;
    
    /**
     * @ORM\OneToMany(targetEntity="Media",mappedBy="item",cascade={"all"})
     */
    private $media;
    
    /**
     * @ORM\ManyToOne(targetEntity="Paragraaf",inversedBy="paragraaf")
     */
    private $paragraaf;
    
    /**
     * @ORM\ManyToOne(targetEntity="Thema")
     * @ORM\OrderBy({"thema"="ASC"})
     */
    private $thema;
    
    /**
     * @ORM\ManyToMany(targetEntity="Item")
     * @ORM\JoinTable(name="item_relatie",
     *      joinColumns={@ORM\JoinColumn(name="item_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="reference_id", referencedColumnName="id")}
     *      )
     */
    private $relaties;
    
    /**
     * @ORM\OneToMany(targetEntity="Link",mappedBy="item",cascade={"all"})
     */
    private $links;
    
    /**
     * @ORM\OneToOne(targetEntity="PublishedItem",cascade={"all"})
     */
    private $publishedItem;    
  
    
    
    public function __construct()
    {
        $this->trefwoorden=new \Doctrine\Common\Collections\ArrayCollection();
        $this->media=new \Doctrine\Common\Collections\ArrayCollection();
        $this->relaties=new \Doctrine\Common\Collections\ArrayCollection();
        $this->links=new \Doctrine\Common\Collections\ArrayCollection();
        $this->homepage=false;
        $this->gepubliceerd=false;
        $this->gemaaktOp=new \DateTime();
        $this->gewijzigdOp=clone $this->gemaaktOp;
        $this->aangevraagd=false;
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

   
    public function setKernboodschap($kernboodschap)
    {
        $this->kernboodschap=$kernboodschap;
    }
    
    public function getKernboodschap()
    {
        return $this->kernboodschap;
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
    
        
    public function getLinks() {
        return $this->links;
    }

    public function setLinks($links) {
        
        foreach($links as $link)
        {
            $link->setItem($this);
        }
        $this->links = $links;
    }

        
    public function getVideo() {
        return $this->video;
    }

    public function setVideo($video) {
        $this->video = $video;
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

            
    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

    public function getGemaaktDoor() {
        return $this->gemaaktDoor;
    }

    public function setGemaaktDoor($gemaaktDoor) {
        $this->gemaaktDoor = $gemaaktDoor;
    }

    public function getGemaaktOp() {
        return $this->gemaaktOp;
    }

    public function setGemaaktOp($gemaaktOp) {
        $this->gemaaktOp = $gemaaktOp;
    }
    
    public function getGewijzigdOp() {
        return $this->gewijzigdOp;
    }

    public function setGewijzigdOp($gewijzigdOp) {
        $this->gewijzigdOp = $gewijzigdOp;
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

    public function getThema() {
        return $this->thema;
    }

    public function setThema($thema) {
        $this->thema = $thema;
    }
    
    public function getPublishedItem() {
        return $this->publishedItem;
    }

    public function setPublishedItem($publishedItem) {
        $this->publishedItem = $publishedItem;
    }

    public function getAangevraagd() {
        return $this->aangevraagd;
    }

    public function setAangevraagd($aangevraagd) {
        $this->aangevraagd = $aangevraagd;
    }
    
        
    
    /**
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->gewijzigdOp=new \DateTime();
    }

    
                    
    public function __toString()
    {
        return $this->titel;
    }

    public function isGepubliceerd()
    {
        return $this->publishedItem!=null;
    }
    
    public function isGewijzigdNaPublicatie()
    {
        return ($this->publishedItem!=null && $this->publishedItem->getGepubliceerdOp()<$this->getGewijzigdOp());
    }

}
