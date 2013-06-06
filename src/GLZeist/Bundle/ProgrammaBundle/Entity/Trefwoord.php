<?php
/*
    This file is part of GroenLinks Zeist Campagnesite.

    GroenLinks Zeist Campagnesite is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    GroenLinks Zeist Campagnesite is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GroenLinks Zeist Campagnesite.  If not, see <http://www.gnu.org/licenses/>.
    
*/

namespace GLZeist\Bundle\ProgrammaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
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
     * @Gedmo\Slug(fields={"trefwoord"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;    


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
    
    public function getZoekterm()
    {
        return strtolower($this->trefwoord);
    }
    
    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

        
    public function __toString()
    {
        return $this->trefwoord;
    }    
}
