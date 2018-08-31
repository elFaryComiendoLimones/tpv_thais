<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 31/08/2018
 * Time: 14:49
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TPVController extends Controller
{
    /**
     *@Route("/tpv", name="tpv")
     */
    public function index(){

        return $this->render('tpv/tpv.html.twig');

    }

}