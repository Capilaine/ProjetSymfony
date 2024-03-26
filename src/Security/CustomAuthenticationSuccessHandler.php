<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CustomAuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        if ($this->isRoleAdmin($token)) {
            $targetUrl = $this->urlGenerator->generate('liste_produits_admin');
        } else {
            $targetUrl = $this->urlGenerator->generate('accueil');
        }

        return new RedirectResponse($targetUrl);
    }

    private function isRoleAdmin(TokenInterface $token): bool
    {
        $roles = $token->getRoleNames();

        return in_array('ROLE_ADMIN', $roles, true);
    }
}