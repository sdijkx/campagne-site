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
class AfbeeldingenService {
    
    private $uploadDirectory;
    
    function __construct($uploadDirectory) {
        $this->uploadDirectory = $uploadDirectory;
    }

    
    public function afbeeldingNaarStdout($item,$id) {
        
        if(!$item instanceof Entity\EntityMetAfbeeldingen) {
            throw new \Exception("Ongeldig type, item moet EntityMetAfbeeldingen implementeren");        
        }
        $afbeeldingen=$item->getAfbeeldingen();
        if($id=='item') {
            $filename=$item->getImageFile();
        } else if($id>=0 && $id<$afbeeldingen->count()) {
            $afbeelding=$afbeeldingen->get(intval($id));
            $filename=$afbeelding->getImageFile();
        } else {
            throw new \Exception("Afbeelding niet gevonden");        
        }

        $dir=$this->uploadDirectory;
        $path=$dir.DIRECTORY_SEPARATOR.$filename;
        $guesser=  \Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser::getInstance();
        $mimeType=$guesser->guess($path);
        ob_clean();
        header("Cache-Control: public"); // HTTP/1.1
        header("Expires: ".date('r',strtotime('+24 hours'))); // Date in the past                
        header('Content-type: '.$mimeType);
        readfile($path);
    }
    
    /**
     * Maak een kopie van de afbeelding met nieuwe bestandsnamen, kopieer de referenties naar (item, publisheditem, hoofdstuk) niet.
     * @param Entity\Afbeelding $afbeelding
     * @param type $name
     * @return Entity\Afbeelding 
     */
    public function maakAfbeeldingKopieMetNieuweBestanden(Entity\Afbeelding $afbeelding,$name) {
        
        $copy = new Entity\Afbeelding;
        $copy->setTitel($afbeelding->getTitel());
        $copy->setImagefile($this->copyImageFileToImageName($afbeelding->getImagefile(), $name));
        $copy->setThumbfile($this->copyImageFileToImageName($afbeelding->getThumbfile(), $name.'-thumb'));
        return $copy;
    }
    
    /**
     *
     * @param type $imageFilename, een image bestandsnaam met extensie.
     * @param type $dest, een doelbestand zonder extensie, met slashes voor subdirectories
     * @return type 
     */
    private function copyImageFileToImageName($imageFilename,$dest) {
        $src=new \SplFileInfo($this->uploadDirectory.DIRECTORY_SEPARATOR.$imageFilename);

        if($src->isFile()) {
            $path=explode('/',$dest);
            $this->mkdirs($path);
            $filepath=implode(DIRECTORY_SEPARATOR,$path);
            $extension=substr($imageFilename,strrpos($src->getBasename(),'.'));
            $target=$this->uploadDirectory.DIRECTORY_SEPARATOR.$filepath.$extension;
            copy($src->getRealPath(),$target);
            return $filepath.$extension;
        }
        throw new \Exception('Fout bij kopieren afbeelding');
        
    }
    
    /**
     * Maak alle directories in het pad, ga ervan uot dat het laatste item op het pad een bestandsnaam is.
     * @param type $path 
     */
    private function mkdirs($path) {
        if(count($path)>1) {
            $dirpath=$this->uploadDirectory.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR,array_slice($path,-1));
            if(!file_exists($dirpath)) {
                mkdir($dirpath,0777,true);
            }
        }
    }
}
