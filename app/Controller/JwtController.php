<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;

use function GaaraHyperf\auth;

#[Controller(prefix: '/jwt')]
class JwtController extends AbstractController
{
    #[GetMapping(path: 'index')]
    public function index()
    {
        $user = auth()->getUser();

        return $this->response->json([
            'message' => 'JWT Example',
            'username' => $user->getIdentifier(),
        ]);
    }

    #[PostMapping(path: 'logout')]
    public function logout()
    {
        return $this->response->json([
            'message' => 'Logged out',
        ]);
    }
}
