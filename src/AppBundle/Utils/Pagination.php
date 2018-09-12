<?php

namespace AppBundle\Utils;

class Pagination{
    
    private $rpp , $page, $rows;
    
    function __construct($rows , $page = 1 , $rpp = 10){
        $this->rows = $rows;
        $this->page = $page;
        $this->rpp = $rpp;
    }
    
    function getOffset(){
        return $this->rpp * ($this->page - 1);
    }
    
    function getRpp(){
        return $this->rpp;
    }
    
    function getLast(){
        return ceil($this->rows / $this->rpp);
    }
    
    function getFirst(){
        return 1;
    }
    
    function getNext(){
        return min($this->page + 1 , $this->getLast());
    }
    
    function getPrevious(){
        return max($this->page - 1 , $this->getFirst());
    }
    
    function setRpp($rpp){
        $this->rpp = $rpp;
    }
    
    function getRange($range = 3){
        $rango = array();
        $last = $this->getLast();
        for ($i = $this->page - $range; $i <= $this->page + $range; $i++) {
             if($i > 0 && $i <= $last){
                 $rango[] = $i;
             }
        }
        return $rango;
    }

    function getActual(){
        return $this->page;
    }
    
}