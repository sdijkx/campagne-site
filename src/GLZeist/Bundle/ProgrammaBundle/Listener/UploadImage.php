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
use GLZeist\Bundle\ProgrammaBundle\Annotation\ImageCollection;

class UploadImage
{
    private $instance;
    private $property;
    private $filenameProperty;
    private $width;
    private $height;
    
    public static function createWithImage(\ReflectionObject $object, $instance, \ReflectionProperty $property,Image $image) {
        $property->setAccessible(true);
        $filenameProperty=$object->getProperty($image->filenameProperty);
        $filenameProperty->setAccessible(true);        
        return new UploadImage($instance, $property, $filenameProperty, $image->width, $image->height);
    }
    public static function createWithImageCollection(\ReflectionObject $object,$instance,ImageCollection $imageCollection) {
        $property=$object->getProperty($imageCollection->fileProperty);
        $property->setAccessible(true);
        $filenameProperty=$object->getProperty($imageCollection->filenameProperty);
        $filenameProperty->setAccessible(true);        
        return new UploadImage($instance, $property, $filenameProperty, $imageCollection->width, $imageCollection->height);
    }

    
    private function __construct($instance, \ReflectionProperty $property,\ReflectionProperty $filenameProperty,$width,$height)
    {
        $property->setAccessible(true);
        $filenameProperty->setAccessible(true);        
        $this->instance=$instance;
        $this->property=$property;
        $this->filenameProperty=$filenameProperty;
        $this->width=$width;
        $this->height=$height;
    }
    
    public function setFilename($filename)
    {
        $this->filenameProperty->setValue($this->instance, $filename);
    }
    
    public function getFilename()
    {
        return $this->filenameProperty->getValue($this->instance);
    }
    
    public function getFilenamePropertyName()
    {
        return $this->filenameProperty->getName();
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


    public function getInstance() {
        return $this->instance;
    }
}