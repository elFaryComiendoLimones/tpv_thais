<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 17/09/2018
 * Time: 17:15
 */

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\ShoppingCart\Cart;



class CommonService
{

    public function shoppingCart(Session $session)
    {
        if (empty($session->get('shoppingCart'))) {
            $session->set('shoppingCart', new Cart());
        }
        $shoppingCart = $session->get('shoppingCart');

        return $shoppingCart;
    }

}