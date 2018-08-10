<?php
/**
 * Created by PhpStorm.
 * User: salvador.perez
 * Date: 10/08/2018
 * Time: 11:49
 */

namespace AppBundle\Security;


use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        return new Response($content, 403);
    }

}