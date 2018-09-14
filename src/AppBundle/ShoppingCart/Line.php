<?php

class Line{
    private $id, $item, $cantidad;
    
    function __construct($id, $item = null, $cantidad = 1){
        $this->id = $id;
        $this->item = $item;
        $this->cantidad = $cantidad;
    }
    
    function getId(){
        return $this->id;
    }
    
    function setId($id){
        $this->id = $id;
    }
    
    function getItem(){
        return $this->item;
    }
    
    function setItem($item){
        $this->item = $item;
    }
    
    function getCantidad(){
        return $this->cantidad;
    }
    
    function setCantidad($cantidad){
        $this->cantidad = $cantidad;
    }
    
    function getAttributesValues(){
        $valoresCompletos = [];
        foreach($this as $atributo => $valor){
            $valoresCompletos[$atributo] = $valor;
        }
        return $valoresCompletos;
    }
    
    function setFromAssociative(array $array){
        foreach($this as $indice => $valor){
            if(isset($array[$indice])){
                $this->$indice = $array[$indice];
            }
        }
    }
}