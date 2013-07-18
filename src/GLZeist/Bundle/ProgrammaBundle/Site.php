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
namespace GLZeist\Bundle\ProgrammaBundle;

use \Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;


class Site
{
    private $banner;
    private $titel;
    private $ondertitel;
    private $filename;

    public function __construct($filename)
    {
        $this->filename=$filename;
        $this->load();
    }
    
    private function load()
    {
        $yaml = new Parser;
        $value=$yaml->parse(file_get_contents($this->filename));
        $this->fromArray($value);
    }
    private function fromArray($values)
    {
        $this->banner=$values['banner'];
        $this->titel=$values['titel'];
        $this->ondertitel=$values['ondertitel'];
    }
    
    public function update()
    {
        $dumper = new Dumper();
        $yaml = $dumper->dump($this->toArray());
        file_put_contents($this->filename, $yaml);        
    }
    
    private function toArray()
    {
        return array(
            'banner' => $this->banner,
            'titel' => $this->titel,
            'ondertitel' => $this->ondertitel
        );
    }
    
    public function getBanner() {
        return $this->banner;
    }

    public function getTitel() {
        return $this->titel;
    }

    public function getOndertitel() {
        return $this->ondertitel;
    }
    public function setBanner($banner) {
        $this->banner = $banner;
    }

    public function setTitel($titel) {
        $this->titel = $titel;
    }

    public function setOndertitel($ondertitel) {
        $this->ondertitel = $ondertitel;
    }



}