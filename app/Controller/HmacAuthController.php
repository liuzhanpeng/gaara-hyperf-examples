<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

use function GaaraHyperf\auth;

#[Controller(prefix: "/hmac-auth")]
class HmacAuthController extends AbstractController
{
    #[GetMapping(path: "userinfo")]
    public function userinfo()
    {
        $user = auth()->getUser();
        return $this->response->json([
            'username' => $user->getIdentifier(),
        ]);
    }

    #[GetMapping(path: "generateSignature")]
    public function generateSignature()
    {
        // 结构: METHOD \n PATH \n QUERY \n APIKEY \n TIMESTAMP [\n NONCE] \n BODY_HASH
        $method = 'GET';
        $path = '/hmac-auth/userinfo';
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

        echo 'generate:';
        print_r($dataToSign);

        $secret = 'KLvoaD3f9qZ3TY8w'; // 与用户关联的密钥
        $signature = hash_hmac('sha256', $dataToSign, $secret);

        return $this->response->json([
            'api_key' => $apiKey,
            'timestamp' => $timestamp,
            'nonce' => $nonce,
            'signature' => $signature,
        ]);

        // GET
        // /hmac-signature-auth/userinfo

        // hmac-user-1
        // 1767599296
        // 498aba5162f6
        // e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855
    }
}
