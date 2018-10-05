<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 17/09/2018
 * Time: 16:58
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket;
use AppBundle\Entity\User;
use AppBundle\Entity\Client;
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
use Symfony\Component\Validator\Constraints\DateTime;

class TicketController extends Controller
{

    /**
     * @Route("/tickets/{page}", defaults={"page"="1"}, name="tickets")
     */
    public function index($page)
    {
        $request = Request::createFromGlobals()->query;
        $employee = $request->get('employee');
        $client = $request->get('client');
        $date = $request->get('date');

        $repo = $this->getDoctrine()->getRepository(Ticket::class);

        $filters = $this->checkFilters($employee, $client, $date);
        $rows = -1;
        if(empty($filters)){
            $rows = $repo->createQueryBuilder('t')
                ->select('count(t.id)')
                ->getQuery()
                ->getSingleScalarResult();
        }else{
            $rows = count($repo->findBy($filters));
        }

        $limit = 7;
        $pagination = new Pagination($rows, $page, $limit);

        if(empty($filters)){
            $tickets = $repo->findBy([],['date_sale' => 'DESC'],$limit, $pagination->getOffset());
        }else{
            $tickets = $repo->findBy($filters,['date_sale' => 'DESC'],$limit, $pagination->getOffset());
        }

        $params = [
            'tickets' => $tickets,
            'pagination' => [
                'next' => $pagination->getNext(),
                'previous' => $pagination->getPrevious(),
                'range' => $pagination->getRange(),
                'actual' => $pagination->getActual()
            ],
            'data_select_filters' => $this->getDataSelectFilters(),
            'filters' => [
                'id_user' => $employee,
                'id_client' => $client,
                'date_sale' => $date
            ]
        ];

        return $this->render('tickets/tickets.html.twig', $params);
    }


    private function checkFilters($employee, $client, $date){
        $em = $this->getDoctrine();
        $filters = [];
        if(!empty($employee)){
            $id_user = $em->getRepository(User::class)->find($employee);
            $filters['id_user'] = $id_user->getId();
        }
        if(!empty($client)) {
            $id_client = $em->getRepository(Client::class)->find($client);
            $filters['id_client'] = $id_client->getId();
        }
        if(!empty($date)){
            $filters['date_sale'] = new \DateTime($date);
        }
        return $filters;
    }

    private function getDataSElectFilters(){
        $em = $this->getDoctrine();

        $users = $em->getRepository(User::class)->findByEnabled(1);
        $clients = $em->getRepository(Client::class)->findByActive(1);

        $dataSelectFilters = [
            'users' => $users,
            'clients' => $clients
        ];
        return $dataSelectFilters;
    }

    /**
     * @Route("/check_in", name="check_in")
     */
    public function checkIn(Request $request)
    {

        $id_client = $request->get('id_client');

        $common = new CommonService();
        $shoppingCart = $common->shoppingCart($this->get('session'));

        $ticket = new Ticket();
        if (!empty($shoppingCart->getCarrito())) {

            //Guardar el ticket
            $em = $this->getDoctrine()->getManager();

            $ticket->setIdUser($this->getUser());

            if(!empty($id_client)){
                $client = $em->getRepository(Client::class)->find($id_client);
                $ticket->setIdClient($client);
            }

            $ticket->setDateSale(new \DateTime("now"));

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

    /**
     * @Route("/data_chart", name="data_chart")
     */
    public function ticketsChart(){
        $repo = $this->getDoctrine()->getRepository(Ticket::class);
        $tickets = $repo->findBy([],['date_sale' => 'DESC'],10);

        $days = $this->getLastNDays(10);

        $data_chart = [];
        $cont = 0;
        foreach ($days as $day){
            $data_chart[$cont]['date'] = $day;
            $units = 0;
            foreach ($tickets as $ticket){
                if(date('Y-m-d', $ticket->getDateSale()->getTimestamp()) == $day){
                    foreach ($ticket->getTicketDetails() as $ticketDetail){
                        $units += $ticketDetail->getPrice();
                    }
                }
            }
            $data_chart[$cont]['units'] = $units;
            $cont++;
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

        $response = $serializer->serialize($data_chart, 'json');

        $jsonResponse = new JsonResponse();
        return $jsonResponse::fromJsonString($response);
    }


    private function getLastNDays($days, $format = 'Y-m-d'){
        $m = date("m"); $de= date("d"); $y= date("Y");
        $dateArray = array();
        for($i=0; $i<=$days-1; $i++){
            $dateArray[] = date($format, mktime(0,0,0,$m,($de-$i),$y));
        }
        return array_reverse($dateArray);
    }

}