<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 19/09/2018
 * Time: 10:40
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Treatment;
use AppBundle\Form\TreatmentType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utils\Pagination;

class TreatmentController extends Controller
{

    /**
     * @Route("/treatments/{page}", defaults={"page"="1"}, name="treatments")
     */
    public function indexAction($page)
    {

        $repo = $this->getDoctrine()->getRepository(Treatment::class);
        $rows = $repo->createQueryBuilder('tr')
            ->select('count(tr.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $limit = 10;
        $pagination = new Pagination($rows, $page, $limit);

        $treatments = $repo->findByActive(1,null,$limit, $pagination->getOffset());


        $params = [
            'treatments' => $treatments,
            'pagination' => [
                'next' => $pagination->getNext(),
                'previous' => $pagination->getPrevious(),
                'range' => $pagination->getRange(),
                'actual' => $pagination->getActual()
            ]
        ];

        return $this->render('treatments/treatments.html.twig', $params);
    }

    /**
     * @Route("/create-treatment", name="create-treatment")
     */
    public function createTreatment(Request $request)
    {
        $treatment = new Treatment();
        $form = $this->createForm(TreatmentType::class, $treatment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$product` variable has also been updated
            $treatment = $form->getData();
            $treatment->setActive(true);

            $trManager = $this->getDoctrine()->getManager();
            $trManager->persist($treatment);
            $trManager->flush();

            /*Resetear el formulario después de insertar correctamente el tratamiento*/
            unset($form);
            unset($treatment);
            $treatment = new Treatment();
            $form = $this->createForm(TreatmentType::class, $treatment);


            return $this->render('treatments/create-treatment.html.twig', ['form' => $form->createView(), 'confirmed' => true]);
        }

        return $this->render('treatments/create-treatment.html.twig', ['form' => $form->createView(), 'confirmed' => '']);
    }

    /**
     *@Route("/delete-treatment/{id}", name="delete-treatment")
     */
    public function deleteTreatment($id){

        $trRepository = $this->getDoctrine()->getRepository(Treatment::class);
        $treatment = $trRepository->find($id);

        $treatment->setActive(0);

        $trManager = $this->getDoctrine()->getManager();
        $trManager->flush();

        return $this->redirectToRoute('treatments');

    }

    /**
     * @Route("/edit-treatment/{id}", defaults={"id"=""}, name="edit-treatment")
     */
    public function editTreatment(Request $request, $id){

        $trRepository = $this->getDoctrine()->getRepository(Treatment::class);
        $treatment = $trRepository->find($id);

        $form = $this->createForm(TreatmentType::class, $treatment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$product` variable has also been updated
            $treatment = $form->getData();
            $treatment->setActive(true);

            $trManager = $this->getDoctrine()->getManager();
            $trManager->merge($treatment);
            $trManager->flush();

            $confirmed = true;

            $this->redirectToRoute('edit-treatment', ['id' => $treatment->getId()]);
        }

        $params = [
            'form' => $form->createView(),
        ];

        if(!empty($confirmed)){
            $params['confirmed'] = $confirmed;
        }

        return $this->render('treatments/edit-treatment.html.twig', $params);

    }

}