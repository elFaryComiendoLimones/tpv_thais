<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Utils\Filter;


class UserController extends Controller
{

    private $request, $validator, $em;

    function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->validator = $this->get('validator');
        $this->em = $this->getDoctrine();
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // replace this example code with whatever you need
        return $this->render('login/login.html.twig');
    }

    /**
     * @Route("/register-admin")
     */
    //Provisional , solo se ha insertado un admin pasando parámetros a mano
    public function registerAdmin()
    {

        $user = new User();
        $user->read();
        //codificar password
        $user->setPassword($user->getPassword());

        $this->em->getManager()->persist($user);
        $this->em->getManager->flush();


        return $this->render('pruebas/prueba.html.twig', ['user' => $user]);

    }

    /**
     * @Route("/login")
     */
    public function login(){
        $user = new User();

        $login = $this->request->query->get('nick');
        $password = $this->request->query->get('password');

        //Comprobar si se loguea con correo o con nick
        if(Filter::isEmail($login)){
            //obtener usuario por email
            $user = $this->em->getRepository(User::class)->findOneByEmail($login);
        }else{
            //obtener usuario por nick
            $user = $this->em->getRepository(User::class)->findOneByNick($login);
        }
        //Comprobar que la clave sea la correcta


    }
}
