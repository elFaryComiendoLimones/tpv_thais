<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\User;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Utils\Filter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Utils\Pagination;


class EmployeeController extends Controller
{


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine();

        $totalSales = count($em->getRepository(Ticket::class)->findAll());
        $totalEmployess = count($em->getRepository(User::class)->findByEnabled(1));
        $totalClients = count($em->getRepository(Client::class)->findByActive(1));

        $params = [
            'totalSales' => $totalSales,
            'totalEmployees' => $totalEmployess,
            'totalClients' => $totalClients
        ];

        return $this->render('dashboard.html.twig', $params);
    }

    /**
     * @Route("/employees/{page}", defaults={"page"="1"}, name="employees")
     */
    public function listUsers($page)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $rows = $repo->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.enabled = :active')
            ->setParameter(':active', 1)
            ->getQuery()
            ->getSingleScalarResult();

        $limit = 10;
        $pagination = new Pagination($rows, $page, $limit);

        $users = $repo->findByEnabled(1,null,$limit, $pagination->getOffset());

        $params = [
            'users' => $users,
            'pagination' => [
                'next' => $pagination->getNext(),
                'previous' => $pagination->getPrevious(),
                'range' => $pagination->getRange(),
                'actual' => $pagination->getActual()
            ]
        ];

        return $this->render('employee/employee.html.twig', $params);
    }

    /**
     * @Route("/delete-user/{id}", name="delete-user")
     */
    public function deleteUser($id)
    {

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        //$product = $productRepository->find(Request::createFromGlobals()->query->get('id'));
        $user = $userRepository->find($id);

        $user->setEnabled(0);

        $userManager = $this->getDoctrine()->getManager();
        $userManager->flush();

        return $this->redirectToRoute('employees');
    }

    /**
     * @Route("/edit-user/{id}", defaults={"id"=""}, name="edit-user")
     */
    public function editUser(Request $request, $id)
    {

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find($id);

        $form = $this->createForm(\AppBundle\Form\EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $user->setEnabled(true);

            /*Añadir imagen*/
            $image = $user->getImage();
            if (!empty($image)) {
                $imageName = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('img_user_directory'),
                    $imageName
                );

                $user->setImage($imageName);
            }

            //$this->userManager->updateUser($user);
            $userManager = $this->getDoctrine()->getManager();
            $userManager->merge($user);
            $userManager->flush();

            $confirmed = true;

            $this->redirectToRoute('edit-user', ['id' => $user->getId()]);

        }

        $params = [
            'form' => $form->createView(),
        ];

        if (!empty($confirmed)) {
            $params['confirmed'] = $confirmed;
        }

        return $this->render('employee/edit-employee.html.twig', $params);

    }


}
