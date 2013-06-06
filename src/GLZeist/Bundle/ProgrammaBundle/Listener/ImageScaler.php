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

class ImageScaler
{
    
    public function getType($filename)
    {
        $guesser=  \Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser::getInstance();
        $mimeType=$guesser->guess($filename);
        
        if($mimeType=='image/jpg')
        {
            return 'jpeg';
        }
        if($mimeType=='image/jpeg')
        {
            return 'jpeg';
        }
        if($mimeType=='image/gif')
        {
            return 'gif';
        }
        if($mimeType=='image/png')
        {
            return 'png';
        }
        
    }
    public function scale($filename,$dest,$type,$width,$height)
    {
        $src;
        if($type=='gif')
        {
            $src=@imagecreatefromgif($filename);
        }
        elseif($type=='png')
        {
            $src=@imagecreatefrompng($filename);
        }
        elseif($type=='jpeg')
        {
            $src=@imagecreatefromjpeg($filename);
        }
        else
        {
            throw new \Exception('Unsupported image type');
        }
        if(!$src)
        {
            throw new \Exception('Error reading image');
        }
        
        $img=@imagecreatetruecolor($width, $height);
        
        $white = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $white);

        
        $srcWidth=imagesx($src);
        $srcHeight=imagesy($src);
        
        if($srcWidth*$height>$srcHeight*$width)
        {
            $scaledWidth=$width;
            $scaledHeight=$srcHeight* $width/$srcWidth;            
            $sx=0;
            $sy=($height-$scaledHeight)/2;
        }
        else
        {
            $scaledWidth=$srcWidth* $height/$srcHeight;
            $scaledHeight=$height;            
            $sx=($width-$scaledWidth)/2;
            $sy=0;
        }
        imagecopyresampled($img,$src,$sx,$sy,0,0,$scaledWidth, $scaledHeight,$srcWidth,$srcHeight);
        imagejpeg($img,$dest,100);

    }
}