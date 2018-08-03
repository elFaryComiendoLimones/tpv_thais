<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 03/08/2018
 * Time: 8:51
 */

namespace AppBundle\Traits;

use Symfony\Component\HttpFoundation\Request;

trait Common{

    function getAttributes() {
        $atributos = [];
        foreach ($this as $atributo => $valor) {
            $atributos[] = $atributo;
        }
        return $atributos;
    }

    function getValues() {
        $valores = [];
        foreach ($this as $valor) {
            $valores[] = $valor;
        }
        return $valores;
    }

    function getAttributesValues() {
        $valoresCompletos = [];
        foreach ($this as $atributo => $valor) {
            $valoresCompletos[$atributo] = $valor;
        }
        return $valoresCompletos;
    }

    function read() {
        foreach ($this as $atributo => $valor) {
            $this->$atributo = Request::createFromGlobals()->query->get($atributo);
        }
    }

    function set(array $array, $pos = 0) {
        foreach ($this as $campo => $valor) {
            if (isset($array[$pos])) {
                $this->$campo = $array[$pos];
            }
            $pos++;
        }
    }

    function setFromAssociative(array $array) {
        foreach ($this as $indice => $valor) {
            if (isset($array[$indice])) {
                $this->$indice = $array[$indice];
            }
        }
    }

    public function __toString() {
        $cadena = get_class() . ': ';
        foreach ($this as $atributo => $valor) {
            $cadena .= $atributo . ': ' . $valor . ', ';
        }
        return substr($cadena, 0, -2);
    }
}