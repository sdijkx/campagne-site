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

namespace GLZeist\Bundle\ProgrammaBundle\Listener;
use GLZeist\Bundle\ProgrammaBundle\Annotation\Image;

class UploadImage
{
    private $object;
    private $instance;
    private $property;
    private $filenameProperty;
    private $width;
    private $height;
    
    public function __construct(\ReflectionObject $object, $instance, \ReflectionProperty $property, Image $image)
    {
        $property->setAccessible(true);
        $filenameProperty=$object->getProperty($image->filenameProperty);
        $filenameProperty->setAccessible(true);        
        $this->object=$object;
        $this->instance=$instance;
        $this->property=$property;
        $this->filenameProperty=$filenameProperty;
        $this->width=$image->width;
        $this->height=$image->height;
    }
    
    public function setFilename($filename)
    {
        $this->filenameProperty->setValue($this->instance, $filename);
    }
    
    public function getFilename()
    {
        return $this->filenameProperty->getValue($this->instance);
    }
    
    public function getFile()
    {
        return $this->property->getValue($this->instance);
    }
    
    public function getWidth()
    {
        return $this->width;
    }
    
    public function getHeight() {
        return $this->height;
    }


}