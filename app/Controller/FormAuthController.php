<?php

declare(strict_types=1);

namespace App\Controller;

use GaaraHyperf\CsrfTokenManager\CsrfTokenManagerResolverInterface;
use Hyperf\Contract\SessionInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;

use function GaaraHyperf\auth;
use function Hyperf\ViewEngine\view;

#[Controller(prefix: "/form-auth")]
class FormAuthController extends AbstractController
{
    #[Inject()]
    private SessionInterface $session;

    #[Inject()]
    private CsrfTokenManagerResolverInterface $csrfTokenManagerResolver;

    #[GetMapping(path: "index")]
    public function index()
    {
        $user = auth()->getUser();
        return view('form-auth/index', [
            'username' => $user->getIdentifier()
        ]);
    }

    #[GetMapping(path: "login")]
    public function login()
    {
        $authenticationError = $this->session->get('authentication_error', null);
        $redirectTo = $this->request->query('redirect_to', null);
        $csrfToken = $this->csrfTokenManagerResolver->resolve()->generate();

        return view('form-auth/login', [
            'authentication_error' => $authenticationError,
            'redirect_to' => $redirectTo,
            'csrf_token' => $csrfToken,
        ]);
    }

    #[PostMapping(path: "logout")]
    public function logout()
    {
        return $this->response->redirect('/form-auth/login');
    }
}
