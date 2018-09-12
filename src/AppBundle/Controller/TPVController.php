<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 31/08/2018
 * Time: 14:49
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Utils\Pagination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;


class TPVController extends Controller
{
    /**
     *@Route("/tpv", name="tpv")
     */
    public function index(Request $request){

        return $this->render('tpv/tpv.html.twig');
    }

    /**
     * @Route("/get-data-products", name="get-data-products")
     */
    public function getData(Request $request)
    {

        $em = $this->getDoctrine();
        /*Obtener el numero total de filas de la tabla*/
        $rows = count($em->getRepository(Product::class)->findByActive(1));

        $page = !empty($request->get('page')) ? $request->get('page') : 1;
        $limit = 15;
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

        if($rows < $limit){
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



}