<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 17/09/2018
 * Time: 16:58
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket;
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

class TicketController extends Controller
{

    /**
     * @Route("/tickets/{page}", defaults={"page"="1"}, name="tickets")
     */
    public function index($page)
    {

        $repo = $this->getDoctrine()->getRepository(Ticket::class);
        $rows = $repo->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $limit = 10;
        $pagination = new Pagination($rows, $page, $limit);

        $tickets = $repo->findBy([],['date_sale' => 'DESC'],$limit, $pagination->getOffset());


        $params = [
            'tickets' => $tickets,
            'pagination' => [
                'next' => $pagination->getNext(),
                'previous' => $pagination->getPrevious(),
                'range' => $pagination->getRange(),
                'actual' => $pagination->getActual()
            ]
        ];

        return $this->render('tickets/tickets.html.twig', $params);

    }

    /**
     * @Route("/check_in", name="check_in")
     */
    public function checkIn()
    {

        $common = new CommonService();
        $shoppingCart = $common->shoppingCart($this->get('session'));

        $ticket = new Ticket();
        if (!empty($shoppingCart->getCarrito())) {

            //Guardar el ticket
            $em = $this->getDoctrine()->getManager();

            $ticket->setIdUser($this->getUser());
            $date = new \DateTime();
            $ticket->setDateSale($date->getTimestamp());

            $em->persist($ticket);
            $em->flush();

        }

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array(
            'typeCode', 'type', 'range',
            'useCaseCode', 'useCase', 'updatedAt', 'updatedBy'));
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getid();
        });
        $serializer = new Serializer(array($normalizer), array($encoder));

        $response = $serializer->serialize($ticket->getId(), 'json');

        $jsonResponse = new JsonResponse();
        return $jsonResponse::fromJsonString($response);
    }

}