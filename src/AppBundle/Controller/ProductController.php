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
use AppBundle\Form\ProductType;

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

    /**
     * @Route("/create-products", name="create-products")
     */
    public function createProducts()
    {
        $product = new Product();
        $dataProvider = array('patatas'=>'con huevos', 'bocata' => 'chorizo');
        $form = $this->createForm(ProductType::class, $product);

        return $this->render('products/create-products.html.twig', ['form' => $form->createView()]);
    }

}