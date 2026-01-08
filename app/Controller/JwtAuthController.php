<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

use function GaaraHyperf\auth;

#[Controller(prefix: "/jwt-auth")]
class JwtAuthController extends AbstractController
{
    #[GetMapping(path: "index")]
    public function index()
    {
        $user = auth()->getUser();
        return $this->response->json([
            'message' => 'JWT Auth Example',
            'username' => $user->getIdentifier(),
        ]);
    }
}
