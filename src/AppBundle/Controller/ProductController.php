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
use Symfony\Component\HttpFoundation\Request;

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
    public function createProducts(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$product` variable has also been updated
            $product = $form->getData();

            /*Añadir imagen*/
            $image = $product->getImage();
            if (!empty($image)) {
                $imageName = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('img_product_directory'),
                    $imageName
                );
            }

            $productManager = $this->getDoctrine()->getManager();
            $productManager->persist($product);
            $productManager->flush();

            //return $this->redirectToRoute('task_success');
        }

        return $this->render('products/create-products.html.twig', ['form' => $form->createView(), 'product' => $product]);
    }

}