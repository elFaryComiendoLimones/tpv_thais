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


    function __construct()
    {

    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('dashboard.html.twig');
    }

    /**
     * @Route("/register-employee", name="register-employee")
     */
    //Provisional , solo se ha insertado un admin pasando parámetros a mano
    public function registerAdmin()
    {

        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $user->read();

        //$userManager->updateUser($user);

        return $this->render('pruebas/prueba.html.twig', ['user' => $user]);
    }


}
