<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

use function GaaraHyperf\auth;

#[Controller(prefix: "/api-key-auth")]
class ApiKeyAuthController extends AbstractController
{
    #[GetMapping(path: "userinfo")]
    public function userinfo()
    {
        $user = auth()->getUser();
        return $this->response->json([
            'username' => $user->getIdentifier(),
        ]);
    }
}
