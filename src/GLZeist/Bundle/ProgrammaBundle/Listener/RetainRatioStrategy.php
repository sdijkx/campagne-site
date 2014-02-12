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

class RetainRatioStrategy implements ScaleStrategy {

    public function scale($width,$height,$srcWidth,$srcHeight) {
        
        if($width<$srcWidth) {
            $scaledWidth=$width;
            $scaledHeight=$srcHeight* $width/$srcWidth;            
            $sx=0;
            $sy=0;
        }
        else
        {
            $scaledWidth=$srcWidth;
            $scaledHeight=$srcHeight;            
            $sx=0;
            $sy=0;
        }
        
        return array('sx' => $sx, 'sy' => $sy, 'scaledWidth' => $scaledWidth, 'scaledHeight' => $scaledHeight,'width' => $scaledWidth,'height' => $scaledHeight);
    }
}
