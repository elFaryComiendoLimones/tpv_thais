<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Utils\Filter;
use Symfony\Component\HttpFoundation\RedirectResponse;


class EmployeeController extends Controller
{


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('dashboard.html.twig');
    }

    /**
     * @Route("/employees", name="employees")
     */
    public function registerAdmin()
    {
        $userManager = $this->getDoctrine()->getRepository(User::class);

        $users = $userManager->findByEnabled(1);

        $params = array('users' => $users);

        return $this->render('employee/employee.html.twig', $params);
    }
    /**
     *@Route("/delete-user/{id}", name="delete-user")
     */
    public function deleteUser($id){

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
    public function editUser(Request $request, $id){



    }



}
