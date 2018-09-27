<?php

namespace AppBundle\ShoppingCart;
use AppBundle\ShoppingCart\Line;

class Cart {

    private $carrito = [
        'product' => [],
        'treatment' => []
    ];
    
    function __construct() {
        $this->type = 'product';
    }
    
    /**
     * Añade un producto al carro o le suma cantidad
     */
    function addLinea(Line $producto, $type) {
        if((isset($this->carrito[$type][$producto->getId()]))){
            $productoPrevio = new Line($producto->getId()); 
            $productoPrevio->setFromAssociative($this->carrito[$type][$producto->getId()]);
            $productoPrevio->setCantidad($productoPrevio->getCantidad() + $producto->getCantidad());
            $this->carrito[$type][$producto->getId()] = $productoPrevio->getAttributesValues();
        }else{
            $this->carrito[$type][$producto->getId()] = $producto->getAttributesValues();
        }
    }

    //añadir con sobreescribiendo cantidad
    function addLinea2(Line $producto, $type) {
        if((isset($this->carrito[$type][$producto->getId()]))){
            $productoPrevio = new Line($producto->getId());
            $productoPrevio->setFromAssociative($this->carrito[$type][$producto->getId()]);
            $productoPrevio->setCantidad($producto->getCantidad());
            $this->carrito[$type][$producto->getId()] = $productoPrevio->getAttributesValues();
        }else{
            $this->carrito[$type][$producto->getId()] = $producto->getAttributesValues();
        }
    }

    function add($id , $producto = null, $cantidad = 1, $type) {
        if($cantidad > 1){
            $this->addLinea2(new Line($id, $producto, $cantidad), $type);
        }else{
            $this->addLinea(new Line($id, $producto, $cantidad), $type);
        }
    }

    /**
     * Elimina un producto del carro
     */    
    function delLinea(Line $producto, $type) {
        unset($this->carrito[$type][$producto->getId()]);
    }

    function del($id, $type) {
        $line = new Line($id);
        $this->delLinea($line, $type);
    }
    
    
    /**
     * Resta a un producto una cantidad      
     */
    function subLinea(Line $producto, $type) {
        if((isset($this->carrito[$type][$producto->getId()]))){
            $productoPrevio = new Line($producto->getId());
            $productoPrevio->setFromAssociative($this->carrito[$type][$producto->getId()]);
            $productoPrevio->setCantidad($productoPrevio->getCantidad() - $producto->getCantidad());
            if($productoPrevio->getCantidad() < 1){
                $this->delLinea($productoPrevio);
            }else{
                $this->carrito[$type][$producto->getId()] = $productoPrevio->getAttributesValues();
            }
        }
    }

    function sub($id, $producto = null, $cantidad = 1, $type) {
        $this->subLinea(new Line($id, $producto, $cantidad), $type);
    }
    
    function getCarrito() {
        return $this->carrito;
    }

    function resetCart(){
        $this->carrito = [];
    }
    
}