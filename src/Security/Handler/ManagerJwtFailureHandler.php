<?php

namespace Infrastructure\Security\Handler;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class ManagerJwtFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function onAuthenticationFailure(Request $request, \Throwable $exception): RedirectResponse
    {
        return new RedirectResponse('/manager/login');
    }
}