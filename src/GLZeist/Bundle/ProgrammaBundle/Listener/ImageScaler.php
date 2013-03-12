<?php
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