<?php

namespace App\Exception\Handler;

use GaaraHyperf\Exception\InvalidAPIKeyException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Throwable;
use Psr\Http\Message\ResponseInterface;

class InvalidAPIKeyExceptionHandler extends ExceptionHandler
{
    /**
     * @param InvalidCredentialsException $throwable
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function handle(Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        $this->stopPropagation();

        return $response->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withStatus(401)
            ->withBody(new SwooleStream('invalid api key'));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof InvalidAPIKeyException;
    }
}
