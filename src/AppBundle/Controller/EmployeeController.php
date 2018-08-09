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
     * @Route("/employees", name="employees")
     */
    //Provisional , solo se ha insertado un admin pasando parámetros a mano
    public function registerAdmin()
    {

        $userManager = $this->getDoctrine()->getRepository(User::class);

        $users = $userManager->findAll();

        $params = array('users' => $users);

        return $this->render('employee/employee.html.twig', $params);
    }


}
