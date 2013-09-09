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

class RSS
{
    private $feed;
    public function __construct($feed)
    {
        $this->feed=$feed;
    }
    
    public function getItems($limit,$offset=0)
    {
        $results=array();
        
        $rss=new \DOMDocument();
        $rss->load($this->feed);
        $channel=$rss->getElementsByTagName('channel')->item(0);
        $itemList=$rss->getElementsByTagName('item');
        
        if($limit==0)
        {
            $limit=-1;
        }
        
        for($i=$offset;$i<$itemList->length && ($limit!=0);$i++,$limit--)
        {
            $item=$itemList->item($i);
            
            $children=$item->childNodes;
            
            $result=array();
            for($j=0;$j<$item->childNodes->length;$j++)
            {
                $child=$item->childNodes->item($j);
                if($child->nodeType==XML_ELEMENT_NODE)
                {
                    $result[$child->nodeName]=$this->_map($child->nodeName,$child->textContent);
                }
            }
            if(count($result)>0)
            {
                $results[]=$result;
            }
        }
        return $results;
    }
    
    private function _map($tag,$value)
    {
        
        if($tag=='pubDate')
        {
            return new \DateTime($value);
        }
        return $value;
    }
    
}