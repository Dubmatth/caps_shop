<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface{

    /**
     * Handles an access denied failure.
     *
     * @param Request $request
     * @param AccessDeniedException $accessDeniedException
     * @return response may return null
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $content = 'Erreur 403, vous n\'avez pas accès à cette partie du site';
        // TODO: Implement handle() method.
        return new Response($content, 403);
    }
}