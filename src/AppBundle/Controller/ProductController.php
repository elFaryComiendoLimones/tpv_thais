<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 09/08/2018
 * Time: 12:22
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Product;

class ProductController extends Controller
{

    /**
     * @Route("/products", name="products")
     */
    public function indexAction()
    {

        $productManager = $this->getDoctrine()->getRepository(Product::class);

        $products = $productManager->findAll();

        $params = array(
            'products' => $products
        );

        return $this->render('products/products.html.twig', $params);
    }

}