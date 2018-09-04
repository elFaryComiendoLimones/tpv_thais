<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 31/08/2018
 * Time: 14:49
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
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
    public function getData()
    {

        $em = $this->getDoctrine();
        $products = $em->getRepository(Product::class)->findByActive(1);

        $encoder = new JsonEncoder();

        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array(
            'typeCode', 'type', 'range',
            'useCaseCode', 'useCase', 'updatedAt', 'updatedBy'));

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getName();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));

        $response = $serializer->serialize($products, 'json');

        $jsonResponse = new JsonResponse();

        return $jsonResponse::fromJsonString($response);
    }



}