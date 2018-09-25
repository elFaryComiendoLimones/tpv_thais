<?php

namespace AppBundle\ShoppingCart;
use AppBundle\ShoppingCart\Line;

class Cart {

    private $carrito = [];
    //Si guarda productos o tratamientos
    private $type;
    
    function __construct() {
        $this->type = 'product';
    }
    
    /**
     * Añade un producto al carro o le suma cantidad
     */
    function addLinea(Line $producto) {
        if((isset($this->carrito[$producto->getId()]))){
            $productoPrevio = new Line($producto->getId()); 
            $productoPrevio->setFromAssociative($this->carrito[$producto->getId()]);
            $productoPrevio->setCantidad($productoPrevio->getCantidad() + $producto->getCantidad());
            $this->carrito[$producto->getId()] = $productoPrevio->getAttributesValues();
        }else{
            $this->carrito[$producto->getId()] = $producto->getAttributesValues();
        }
    }

    function add($id , $producto = null, $cantidad = 1) {
        if($cantidad > 1){
            $this->addLinea2(new Line($id, $producto, $cantidad));    
        }else{
            $this->addLinea(new Line($id, $producto, $cantidad));    
        }
    }
    
    //añadir con sobreescribiendo cantidad
    function addLinea2(Line $producto) {
        if((isset($this->carrito[$producto->getId()]))){
            $productoPrevio = new Line($producto->getId()); 
            $productoPrevio->setFromAssociative($this->carrito[$producto->getId()]);
            $productoPrevio->setCantidad($producto->getCantidad());
            $this->carrito[$producto->getId()] = $productoPrevio->getAttributesValues();
        }else{
            $this->carrito[$producto->getId()] = $producto->getAttributesValues();
        }
    }

    /**
     * Elimina un producto del carro
     */    
    function delLinea(Line $producto) {
        unset($this->carrito[$producto->getId()]);
    }

    function del($id) {
        $line = new Line($id);
        $this->delLinea($line);
    }
    
    
    /**
     * Resta a un producto una cantidad      
     */
    function subLinea(Line $producto) {
        if((isset($this->carrito[$producto->getId()]))){
            $productoPrevio = new Line($producto->getId());
            $productoPrevio->setFromAssociative($this->carrito[$producto->getId()]);
            $productoPrevio->setCantidad($productoPrevio->getCantidad() - $producto->getCantidad());
            if($productoPrevio->getCantidad() < 1){
                $this->delLinea($productoPrevio);
            }else{
                $this->carrito[$producto->getId()] = $productoPrevio->getAttributesValues();
            }
        }
    }

    function sub($id, $producto = null, $cantidad = 1) {
        $this->subLinea(new Line($id, $producto, $cantidad));
    }
    
    function getCarrito() {
        return $this->carrito;
    }

    function resetCart(){
        $this->carrito = [];
    }

    public function getType()
    {
        return $this->type;
    }


    public function setType($type)
    {
        $this->type = $type;
    }


    
}