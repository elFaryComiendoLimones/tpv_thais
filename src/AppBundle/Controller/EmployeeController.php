<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\UserBundle\Form\Type\RegistrationFormType;
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
    public function listUsers()
    {
        $userManager = $this->getDoctrine()->getRepository(User::class);

        $users = $userManager->findByEnabled(1);

        $params = array('users' => $users);

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

            $this->redirectToRoute('edit-product', ['id' => $user->getId()]);

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
