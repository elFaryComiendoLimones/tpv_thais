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

}