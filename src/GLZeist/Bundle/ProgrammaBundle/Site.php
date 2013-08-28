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

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\HttpFoundation\File\File;

class Site
{
    private $banner;
    private $titel;
    private $ondertitel;
    private $filename;
    private $twitter;

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
        $this->twitter=$this->getFromArray($values,'twitter','groenlinks zeist');
        try
        {
            $this->banner=new File($values['banner']);
        }
        catch(\Exception $e)
        {
            $this->banner=null;
        }
        $this->titel=$values['titel'];
        $this->ondertitel=$values['ondertitel'];
    }
    private function getFromArray($ar,$key,$default)
    {
        return isset($ar[$key])?$ar[$key]:$default;
    }

    public function update($data=array())
    {
        foreach($data as $key => $value)
        {
            if($key=='banner')
            {
                if($value instanceof \Symfony\Component\HttpFoundation\File\File)
                {
                    $this->updateAndScaleBanner($value);
                }
            }
            else
            {
                $this->$key=$value;    
            }
        }
                        
        $dumper = new Dumper();
        $yaml = $dumper->dump($this->toArray());
        file_put_contents($this->filename, $yaml);        
    }
    
    public function updateAndScaleBanner(\Symfony\Component\HttpFoundation\File\File $banner,$width=960,$height=149)
    {
        $scaler=new \GLZeist\Bundle\ProgrammaBundle\Listener\ImageScaler();
        $filename=$banner->getFileInfo()->getRealPath();
        $dest=$this->getDirectory().DIRECTORY_SEPARATOR.'banner.jpg';
        $type=$scaler->getType($filename);
        $scaler->scale($filename, $dest, $type, 1024, 149);
        $this->banner=new \Symfony\Component\HttpFoundation\File\File($dest);
    }
    
    private function toArray()
    {
        if($this->banner instanceof File)
        {
            $banner=$this->banner->getRealPath();
        }
        else
        {
            $banner=$this->banner;
        }
        return array(
            'banner' => $banner,
            'titel' => $this->titel,
            'ondertitel' => $this->ondertitel,
            'twitter' => $this->twitter
                
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
    
    public function getTwitter() {
        return $this->twitter;
    }

    public function setTwitter($twitter) {
        $this->twitter = $twitter;
    }

    
    public function getDirectory()
    {
        return dirname($this->filename);
    }
    

}