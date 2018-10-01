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
use AppBundle\Utils\Pagination;

class ProductController extends Controller
{

    /**
     * @Route("/products/{page}", defaults={"page"="1"}, name="products")
     */
    public function indexAction($page)
    {

        $repo = $this->getDoctrine()->getRepository(Product::class);
        $rows = $repo->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where('p.active = :active')
            ->setParameter(':active', 1)
            ->getQuery()
            ->getSingleScalarResult();

        $limit = 10;
        $pagination = new Pagination($rows, $page, $limit);

        $products = $repo->findByActive(1,null,$limit, $pagination->getOffset());


        $params = [
            'products' => $products,
            'pagination' => [
                'next' => $pagination->getNext(),
                'previous' => $pagination->getPrevious(),
                'range' => $pagination->getRange(),
                'actual' => $pagination->getActual()
            ]
        ];

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
                $product->setImage($imageName);
            }

            $productManager = $this->getDoctrine()->getManager();
            $productManager->persist($product);
            $productManager->flush();

            /*Resetear el formulario después de insertar correctamente el producto*/
            unset($form);
            unset($product);
            $product = new Product();
            $form = $this->createForm(ProductType::class, $product);


            return $this->render('products/create-products.html.twig', ['form' => $form->createView(), 'confirmed' => true]);
        }

        return $this->render('products/create-products.html.twig', ['form' => $form->createView(), 'confirmed' => '']);
    }

    /**
     *@Route("/delete-product/{id}", name="delete-product")
     */
    public function deleteProduct($id){

        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        //$product = $productRepository->find(Request::createFromGlobals()->query->get('id'));
        $product = $productRepository->find($id);

        $product->setActive(0);

        $productManager = $this->getDoctrine()->getManager();
        $productManager->flush();

        return $this->redirectToRoute('products');

    }

    /**
     * @Route("/edit-product/{id}", defaults={"id"=""}, name="edit-product")
     */
    public function editProduct(Request $request, $id){

        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        //$product = $productRepository->find(Request::createFromGlobals()->query->get('id'));
        $product = $productRepository->find($id);

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
                $product->setImage($imageName);
            }

            $productManager = $this->getDoctrine()->getManager();
            $productManager->merge($product);
            $productManager->flush();

            $confirmed = true;

            //return $this->render('products/products.html.twig', ['form' => $form->createView(), 'confirmed' => $product]);
            $this->redirectToRoute('edit-product', ['id' => $product->getId()]);
        }

        $params = [
            'form' => $form->createView(),
        ];

        if(!empty($confirmed)){
            $params['confirmed'] = $confirmed;
        }

        return $this->render('products/edit-product.html.twig', $params);

    }

}