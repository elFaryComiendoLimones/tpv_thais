<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 31/08/2018
 * Time: 9:00
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Client;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ClientType;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use AppBundle\Utils\Pagination;

class ClientController extends Controller
{

    /**
     * @Route("/clients{page}", defaults={"page"="1"}, name="clients")
     */
    public function indexAction($page)
    {

        $repo = $this->getDoctrine()->getRepository(Client::class);
        $rows = $repo->createQueryBuilder('c')
            ->select('count(c.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $limit = 10;
        $pagination = new Pagination($rows, $page, $limit);

        $clients = $repo->findByActive(1,null,$limit, $pagination->getOffset());


        $params = [
            'clients' => $clients,
            'pagination' => [
                'next' => $pagination->getNext(),
                'previous' => $pagination->getPrevious(),
                'range' => $pagination->getRange(),
                'actual' => $pagination->getActual()
            ]
        ];

        return $this->render('client/clients.html.twig', $params);
    }

    /**
     * @Route("/create-client", name="create-client")
     */
    public function createClient(Request $request)
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $client = $form->getData();

            $client->setActive(true);

            $clientManager = $this->getDoctrine()->getManager();
            $clientManager->persist($client);
            $clientManager->flush();

            /*Resetear el formulario después de insertar correctamente el cliento*/
            unset($form);
            unset($client);
            $client = new Client();
            $form = $this->createForm(ClientType::class, $client);


            return $this->render('client/create-client.html.twig', ['form' => $form->createView(), 'confirmed' => true]);
        }

        return $this->render('client/create-client.html.twig', ['form' => $form->createView(), 'confirmed' => '']);
    }

    /**
     *@Route("/delete-client/{id}", name="delete-client")
     */
    public function deleteClient($id){

        $clientRepository = $this->getDoctrine()->getRepository(Client::class);
        //$client = $clientRepository->find(Request::createFromGlobals()->query->get('id'));
        $client = $clientRepository->find($id);

        $client->setActive(0);

        $clientManager = $this->getDoctrine()->getManager();
        $clientManager->flush();

        return $this->redirectToRoute('clients');

    }

    /**
     * @Route("/edit-client/{id}", defaults={"id"=""}, name="edit-client")
     */
    public function editClient(Request $request, $id){

        $clientRepository = $this->getDoctrine()->getRepository(Client::class);
        //$client = $clientRepository->find(Request::createFromGlobals()->query->get('id'));
        $client = $clientRepository->find($id);

        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $form->getData();

            $client->setActive(true);

            $clientManager = $this->getDoctrine()->getManager();
            $clientManager->merge($client);
            $clientManager->flush();

            $confirmed = true;

            $this->redirectToRoute('edit-client', ['id' => $client->getId()]);
        }

        $params = [
            'form' => $form->createView(),
        ];

        if(!empty($confirmed)){
            $params['confirmed'] = $confirmed;
        }

        return $this->render('client/edit-client.html.twig', $params);

    }

    /**
     * @Route("/get-data", name="get-data")
     */
    public function getData(Request $request)
    {

        $clientManager = $this->getDoctrine()->getRepository(Client::class);
        $client = $clientManager->findById($request->request->get('id'));

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $response = $serializer->serialize($client, 'json');

        $jsonResponse = new JsonResponse();

        return $jsonResponse::fromJsonString($response);
    }

}