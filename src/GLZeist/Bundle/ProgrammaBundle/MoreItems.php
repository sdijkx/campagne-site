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

class MoreItems {

    private $items;
    private $limit;
    private $offset;
    private $count;
    private $path;
    
    public function __construct($items,$count,$limit,$offset,$path)
    {
        if($limit==0)
        {
            throw new \Exception('limit moet groter gelijk zijn aan 1');
        }
        $this->items=$items;
        $this->count=$count;
        $this->limit=$limit;
        $this->offset=$offset;
        $this->path=$path;
    }
    
    public function more()
    {
        return $this->offset+$this->limit < $this->count;
    }
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function getItems() {
        return $this->items;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function getOffset() {
        return $this->offset;
    }

    public function getCount() {
        return $this->count;
    }


    
}
