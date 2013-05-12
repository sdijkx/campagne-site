<?php

namespace GLZeist\Bundle\ProgrammaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Published
 *
 * @ORM\Entity(repositoryClass="GLZeist\Bundle\ProgrammaBundle\Repository\PublishedItemRepository")
 * @ORM\HasLifecycleCallbacks
 */
class PublishedItem
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
     * @Gedmo\Slug(fields={"titel"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $gepubliceerdOp;
    
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
     * @ORM\ManyToMany(targetEntity="PublishedItem")
     * @ORM\JoinTable(name="item_published_relatie",
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
     * @ORM\OneToOne(targetEntity="Item")
     */
    private $item;    
    
    /**
     * @ORM\Column(name="zoek_tekst",type="text",nullable=true)
     */
    private $zoektekst;
    
    /**
     * @ORM\Column(name="zoek_trefwoorden",type="text",nullable=true)
     */    
    private $zoektrefwoorden;
    
    
    public function __construct()
    {
        $this->trefwoorden=new \Doctrine\Common\Collections\ArrayCollection();
        $this->media=new \Doctrine\Common\Collections\ArrayCollection();
        $this->relaties=new \Doctrine\Common\Collections\ArrayCollection();
        $this->links=new \Doctrine\Common\Collections\ArrayCollection();
        $this->homepage=false;
        $this->gepubliceerdOp=new \DateTime();
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
            
    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

    public function getGepubliceerdOp() {
        return $this->gepubliceerdOp;
    }

    public function setGepubliceerdOp($gepubliceerdOp) {
        $this->gepubliceerdOp = $gepubliceerdOp;
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

    public function getZoektekst() {
        return $this->zoektekst;
    }

    public function setZoektekst($zoektekst) {
        $this->zoektekst = $zoektekst;
    }

    public function getZoektrefwoorden() {
        return $this->zoektrefwoorden;
    }

    public function setZoektrefwoorden($zoektrefwoorden) {
        $this->zoektrefwoorden = $zoektrefwoorden;
    }
    
    public function getItem() {
        return $this->item;
    }

    public function setItem($item) {
        $this->item = $item;
    }

                        
    public function __toString()
    {
        return $this->titel;
    }
}
