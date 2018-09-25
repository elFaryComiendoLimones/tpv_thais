<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 17/09/2018
 * Time: 17:10
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket_detail;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Product;
use AppBundle\Entity\Treatment;
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

class TicketDetailController extends Controller
{

    /**
     * @Route("/save_details", name="save_details")
     */
    public function checkIn(Request $request){

        $common = new CommonService();
        $shoppingCart = $common->shoppingCart($this->get('session'));

        $em = $this->getDoctrine();

        $ticketDetails = [];
        if(!empty($shoppingCart->getCarrito())){

            $idTicket = $request->get('id');
            $ticket = $em->getRepository(Ticket::class)->find($idTicket);

            //Guardar el ticket
            $em = $this->getDoctrine()->getManager();

            foreach ($shoppingCart->getCarrito() as $line){
                $ticketDetail = new Ticket_detail();
                $ticketDetail->setIdTicket($ticket);
                if($shoppingCart->getType() == 'product'){
                    $product = $em->getRepository(Product::class)->find($line['item']->getId());
                    $ticketDetail->setIdProduct($product);
                }elseif ($shoppingCart->getType() == 'treatment'){
                    $product = $em->getRepository(Treatment::class)->find($line['item']->getId());
                    $ticketDetail->setIdTreatment($product);
                }

                $ticketDetail->setQuantity($line['cantidad']);
                $ticketDetail->setPrice(number_format($line['cantidad'] * $line['item']->getPrice(), 2, '.', ','));
                $em->persist($ticketDetail);
                $em->flush();

                $ticketDetails[] = $ticketDetail->getId();
            }

            $shoppingCart->resetCart();

        }

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array(
            'typeCode', 'type', 'range',
            'useCaseCode', 'useCase', 'updatedAt', 'updatedBy'));
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer(array($normalizer), array($encoder));

        $response = $serializer->serialize($ticketDetails, 'json');

        $jsonResponse = new JsonResponse();
        return $jsonResponse::fromJsonString($response);
    }

    /**
     * @Route("/get_details", name="get_details")
     */
    public function getDetails(Request $request){

        $id = $request->get('id_product');

        $em = $this->getDoctrine();
        $ticket_detail = $em->getRepository(Ticket_detail::class)->findByIdTicket($id);

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array(
            'typeCode', 'type', 'range',
            'useCaseCode', 'useCase', 'updatedAt', 'updatedBy'));
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer(array($normalizer), array($encoder));

        $response = $serializer->serialize($ticket_detail, 'json');

        $jsonResponse = new JsonResponse();
        return $jsonResponse::fromJsonString($response);

    }

}