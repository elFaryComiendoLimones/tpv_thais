<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 30/08/2018
 * Time: 14:04
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Provider;
use AppBundle\Form\ProviderType;


class ProviderController extends Controller
{

    /**
     * @Route("/providers", name="providers")
     */
    public function listProviders()
    {
        $providerManager = $this->getDoctrine()->getRepository(Provider::class);

        $providers = $providerManager->findByActive(1);

        $params = array('providers' => $providers);

        return $this->render('provider/providers.html.twig', $params);
    }

    /**
     * @Route("/create-provider", name="create-provider")
     */
    public function createProvider(Request $request)
    {
        $provider = new Provider();
        $form = $this->createForm(ProviderType::class, $provider);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$provider` variable has also been updated
            $provider = $form->getData();
            $provider->setActive(true);


            $providerManager = $this->getDoctrine()->getManager();
            $providerManager->persist($provider);
            $providerManager->flush();

            /*Resetear el formulario después de insertar correctamente el producto*/
            unset($form);
            unset($provider);
            $provider = new Provider();
            $form = $this->createForm(ProviderType::class, $provider);


            return $this->render('provider/create-provider.html.twig', ['form' => $form->createView(), 'confirmed' => true]);
        }

        return $this->render('provider/create-provider.html.twig', ['form' => $form->createView(), 'confirmed' => '']);
    }

    /**
     *@Route("/delete-provider/{id}", name="delete-provider")
     */
    public function deleteProvider($id){

        $providerRepository = $this->getDoctrine()->getRepository(Provider::class);
        //$provider = $providerRepository->find(Request::createFromGlobals()->query->get('id'));
        $provider = $providerRepository->find($id);

        $provider->setActive(0);

        $providerManager = $this->getDoctrine()->getManager();
        $providerManager->flush();

        return $this->redirectToRoute('providers');

    }

    /**
     * @Route("/edit-provider/{id}", defaults={"id"=""}, name="edit-provider")
     */
    public function editProvider(Request $request, $id){

        $providerRepository = $this->getDoctrine()->getRepository(Provider::class);
        $provider = $providerRepository->find($id);

        $form = $this->createForm(ProviderType::class, $provider);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $provider = $form->getData();

            $provider->setActive(true);

            $providerManager = $this->getDoctrine()->getManager();
            $providerManager->merge($provider);
            $providerManager->flush();

            $confirmed = true;

            $this->redirectToRoute('edit-provider', ['id' => $provider->getId()]);
        }

        $params = [
            'form' => $form->createView(),
        ];

        if(!empty($confirmed)){
            $params['confirmed'] = $confirmed;
        }

        return $this->render('provider/edit-provider.html.twig', $params);

    }

}