<?php

namespace App\Exception\Handler;

use GaaraHyperf\Exception\InvalidCredentialsException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Throwable;
use Psr\Http\Message\ResponseInterface;

class InvalidCredentialsExceptionHandler extends ExceptionHandler
{
    /**
     * @param InvalidCredentialsException $throwable
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function handle(Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        $this->stopPropagation();

        $errorData = [
            'msg' => '用户名或密码错误',
        ];

        return $response->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withStatus(200)
            ->withBody(new SwooleStream(json_encode($errorData)));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof InvalidCredentialsException;
    }
}
