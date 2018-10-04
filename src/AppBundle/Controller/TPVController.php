<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 31/08/2018
 * Time: 14:49
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Client;
use AppBundle\Entity\Treatment;
use AppBundle\Utils\Pagination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\ShoppingCart\Cart;
use AppBundle\Service\CommonService;


class TPVController extends Controller
{
    /**
     * @Route("/tpv", name="tpv")
     */
    public function index(CommonService $common)
    {
        $shoppingCart = $common->shoppingCart($this->get('session'));

        $totalPrice = 0.00;
        $cant = 0;
        if (!empty($shoppingCart->getCarrito())) {
            foreach ($shoppingCart->getCarrito() as $type) {
                foreach ($type as $line){
                    //$totalPrice = number_format((float)$totalPrice + $line['cantidad'] * $line['item']->getPrice(), 2, '.', ',');
                    $totalPrice += number_format(floatval($line['cantidad'] * $line['item']->getPrice()), 2, '.', ',');
                    $cant++;
                }
            }
        }

        /*Lista de clientes para select de asociar cliente*/
        $clients = $this->getDoctrine()->getRepository(Client::class)->findByActive(1);

        $params = [
            'shoppingCart' => $shoppingCart->getCarrito(),
            'totalPrice' => $totalPrice . ' €',
            'cant' => $cant,
            'client_list' => $clients
        ];

        return $this->render('tpv/tpv.html.twig', $params);
    }

    /**
     * @Route("/get-data-products", name="get-data-products")
     */
    public function getDataProducts(Request $request)
    {

        $em = $this->getDoctrine();
        /*Obtener el numero total de filas de la tabla*/
        //$rows = count($em->getRepository(Product::class)->findByActive(1));
        $repo = $this->getDoctrine()->getRepository(Product::class);
        $rows = $repo->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $page = !empty($request->get('page')) ? $request->get('page') : 1;
        $limit = 16;
        $pagination = new Pagination($rows, $page, $limit);

        $products = $em->getRepository(Product::class)->findByActive(1, null, $limit, $pagination->getOffset());

        $data = [
            'products' => $products,
            'pagination' => [
                'visible' => true,
                'next' => $pagination->getNext(),
                'previous' => $pagination->getPrevious(),
                'actual' => $pagination->getActual()
            ]
        ];

        if ($rows < $limit) {
            $data['pagination']['visible'] = false;
        }

        $encoder = new JsonEncoder();

        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array(
            'typeCode', 'type', 'range',
            'useCaseCode', 'useCase', 'updatedAt', 'updatedBy'));
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getName();
        });
        $serializer = new Serializer(array($normalizer), array($encoder));

        $response = $serializer->serialize($data, 'json');

        $jsonResponse = new JsonResponse();

        return $jsonResponse::fromJsonString($response);
    }

    /**
     * @Route("/get-data-treatments", name="get-data-treatments")
     */
    public function getDataTreatments(Request $request)
    {

        $em = $this->getDoctrine();
        /*Obtener el numero total de filas de la tabla*/
        $repo = $this->getDoctrine()->getRepository(Treatment::class);
        $rows = $repo->createQueryBuilder('tr')
            ->select('count(tr.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $page = !empty($request->get('page')) ? $request->get('page') : 1;
        $limit = 16;
        $pagination = new Pagination($rows, $page, $limit);

        $treatments = $em->getRepository(Treatment::class)->findByActive(1, null, $limit, $pagination->getOffset());

        $data = [
            'treatments' => $treatments,
            'pagination' => [
                'visible' => true,
                'next' => $pagination->getNext(),
                'previous' => $pagination->getPrevious(),
                'actual' => $pagination->getActual()
            ]
        ];

        if ($rows < $limit) {
            $data['pagination']['visible'] = false;
        }

        $encoder = new JsonEncoder();

        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array(
            'typeCode', 'type', 'range',
            'useCaseCode', 'useCase', 'updatedAt', 'updatedBy'));
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getName();
        });
        $serializer = new Serializer(array($normalizer), array($encoder));

        $response = $serializer->serialize($data, 'json');

        $jsonResponse = new JsonResponse();

        return $jsonResponse::fromJsonString($response);
    }

    /**
     * @Route("/manage_cart", name="manage_cart")
     */
    public function manageCart(Request $request)
    {
        $id = $request->get('id');
        $action = $request->get('action');
        $type = $request->get('type');

        $em = $this->getDoctrine();
        $common = new CommonService();
        $shoppingCart = $common->shoppingCart($this->get('session'));

        if ($type == 'product') {
            $product = $em->getRepository(Product::class)->find($id);
        } elseif ($type == 'treatment') {
            $product = $em->getRepository(Treatment::class)->find($id);
        }

        switch ($action) {
            case 'sum':
                $cant = $request->get('cant');
                $shoppingCart->add($id, $product, $cant, $type);
                break;
            case 'minus':
                $shoppingCart->sub($id, $product, 1, $type);
                break;
            case 'del':
                $shoppingCart->del($id, $type);
                break;
            case 'reset':
                $shoppingCart->resetCart();
                break;
        }

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array(
            'typeCode', 'type', 'range',
            'useCaseCode', 'useCase', 'updatedAt', 'updatedBy'));
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getName();
        });
        $serializer = new Serializer(array($normalizer), array($encoder));

        $response = $serializer->serialize($shoppingCart, 'json');

        $jsonResponse = new JsonResponse();
        return $jsonResponse::fromJsonString($response);
    }

    /**
     * @Route("/get_shopping_cart", name="get_shopping_cart")
     */
    public function getShoppingCart(CommonService $common)
    {
        $shoppingCart = $common->shoppingCart($this->get('session'));

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array(
            'typeCode', 'type', 'range',
            'useCaseCode', 'useCase', 'updatedAt', 'updatedBy'));
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getName();
        });
        $serializer = new Serializer(array($normalizer), array($encoder));

        $response = $serializer->serialize($shoppingCart->getCarrito(), 'json');

        $jsonResponse = new JsonResponse();
        return $jsonResponse::fromJsonString($response);
    }

}