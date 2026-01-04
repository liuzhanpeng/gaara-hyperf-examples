<?php

declare(strict_types=1);

namespace App\FailureHandler;

use GaaraHyperf\Authenticator\AuthenticationFailureHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use GaaraHyperf\Exception\AuthenticationException;
use GaaraHyperf\Passport\Passport;
use Psr\Http\Message\ResponseInterface;

class APIKeyFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function handle(string $guardName, ServerRequestInterface $request, AuthenticationException $exception, ?Passport $passport = null): ResponseInterface
    {
        $response = new \Hyperf\HttpMessage\Server\Response();
        $response = $response->withStatus(401)
            ->withHeader('Content-Type', 'application/json');

        $data = [
            'error' => 'unauthorized',
            'error_description' => $exception->getMessage(),
        ];

        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
