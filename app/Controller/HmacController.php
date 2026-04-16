<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

use function GaaraHyperf\auth;

#[Controller(prefix: '/hmac')]
class HmacController extends AbstractController
{
    #[GetMapping(path: 'userinfo')]
    public function userinfo()
    {
        $user = auth()->getUser();
        return $this->response->json([
            'username' => $user->getIdentifier(),
        ]);
    }

    #[GetMapping(path: 'generateSignature')]
    public function generateSignature()
    {
        // 签名内容: METHOD\nPATH\nQUERY\nAPIKEY\nTIMESTAMP[\nNONCE]\nBODY_HASH
        $method = 'GET';
        $path = '/hmac/userinfo';
        $query = '';
        $apiKey = 'hmac-user-1';
        $timestamp = time();
        $nonce = bin2hex(random_bytes(6)); // 如果启用了nonce，则需要生成一个随机字符串
        $bodyHash = hash('sha256', ''); // GET请求，body为空
        $dataToSign = implode("\n", [
            $method,
            $path,
            $query,
            $apiKey,
            $timestamp,
            $nonce,
            $bodyHash,
        ]);

        $secret = 'KLvoaD3f9qZ3TY8w'; // 与用户关联的密钥
        $signature = hash_hmac('sha256', $dataToSign, $secret);

        return $this->response->json([
            'api_key' => $apiKey,
            'timestamp' => $timestamp,
            'nonce' => $nonce,
            'signature' => $signature,
        ]);
    }
}
