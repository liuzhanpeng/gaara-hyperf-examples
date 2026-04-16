<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
// $privateKey = base64_encode($privateKey);

// echo $privateKey . PHP_EOL;

// $publicKey = file_get_contents(__DIR__ . '/es256-public.pem');
// $publicKey = base64_encode($publicKey);
// echo $publicKey . PHP_EOL;

echo password_hash('123456', PASSWORD_BCRYPT) . PHP_EOL;
