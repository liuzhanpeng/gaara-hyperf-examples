<?php

declare(strict_types=1);
use GaaraHyperf\Authenticator\OpaqueTokenSuccessHandler;
use GaaraHyperf\EventListener\LoginAttemptLimitListener;
use GaaraHyperf\JWT\JWTSuccessHandler;

/*
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'guards' => [
        'form-login' => [
            'matcher' => [
                'pattern' => '^/form-login/',
                'logout_path' => '/form-login/logout',
                'exclusions' => [
                    '/form-login/login', // 登录页面
                ],
            ],
            'user_provider' => [
                'type' => 'memory',  // 为了简化示例，使用内存用户提供者，实际项目中请使用数据库等持久化存储
                'users' => [
                    'admin' => [
                        'password' => '$2y$10$EFSGHFCGlui7PDCztM9O9.L4Lk4Oy/uyenm1EvMgmz5oC0JKoAov2', // 123456
                    ],
                ],
            ],
            'authenticators' => [
                'form_login' => [ // 内置表单登录认证器
                    'check_path' => '/form-login/check-login', // 必须; 登录表单提交路径
                    'target_path' => '/form-login/index', // 可选; 登录成功跳转路径
                    'failure_path' => '/form-login/login', // 可选;登录失败跳转路径
                    // 'redirect_enabled' => true, // 可选;是否启用登录成功后的重定向
                    // 'redirect_field' => 'redirect_to', // 可选;重定向目标路径参数名
                    // 'username_field' => 'username', // 可选;用户名参数名
                    // 'password_field' => 'password', // 可选;密码参数名
                    // 'error_message' => '用户名或密码错误', // 可选;登录失败错误消息; 支持字符串或回调函数; 回调函数参数为 AuthenticationException 实例
                    // 'csrf_enabled' => true, // 可选;是否启用CSRF保护
                    // 'csrf_id' => 'authenticate', // 可选;CSRF令牌ID
                    // 'csrf_field' => '_csrf_token', // 可选;CSRF令牌参数名
                    // 'csrf_token_manager' => 'default', // 可选;CSRF令牌管理器服务名称
                    // 'success_handler' => [ // 可选，登录成功处理器配置; 没有参数时可以直接配置类名字符串
                    //     'class' => CustomSuccessHandler::class,
                    //     'params' => [],
                    // ],
                    // 'failure_handler' => [ // 可选，登录失败处理器配置
                    //     'class' => CustomFailureHandler::class,
                    //     'params' => [],
                    // ],
                ],
            ],
            'token_storage' => [
                'type' => 'session', // form-login 认证器使用 session 存储 token, 不然登录状态无法保持
                'prefix' => 'form-login',
            ],
            'unauthenticated_handler' => [
                'type' => 'redirect', //  from_login认证使用redirect类型的未认证处理器, 当用户未认证访问受保护资源时会重定向到登录页面
                'target_path' => '/form-login/login', // type == redirect时必填，重定向目标路径
                // 'redirect_enabled' => true, // type == redirect时可选，是否启用重定向，默认true
                // 'redirect_field' => 'redirect_to' // type == redirect时可选，重定向目标路径参数名，默认redirect_to
                // 'error_field' => 'authentication_error', // type == redirect时可选，存放认证错误消息的session键名，默认authentication_error
                // 'error_message' => '未认证或已登出，请重新登录', // type == redirect时可选，认证错误消息，默认'未认证或已登出
            ],
        ],

        'json-login' => [
            'matcher' => [
                'pattern' => '^/json-login/',
                'logout_path' => '/json-login/logout',
            ],
            'user_provider' => [
                'type' => 'memory',
                'users' => [
                    'admin' => [
                        'password' => '$2y$10$EFSGHFCGlui7PDCztM9O9.L4Lk4Oy/uyenm1EvMgmz5oC0JKoAov2', // 123456
                    ],
                ],
            ],
            'authenticators' => [
                'json_login' => [
                    'check_path' => '/json-login/check-login', // JSON登录请求路径
                    // 'username_field' => 'username', // 用户名字段名
                    // 'password_field' => 'password', // 密码字段名
                    'success_handler' => [ // 可选，登录成功处理器配置; 无状态认证时一般都需要配置, 用于生成access token返回给客户端
                        'class' => OpaqueTokenSuccessHandler::class,
                        'params' => [
                            'token_manager' => 'default',
                        ],
                    ],
                    // 'failure_handler' => CustomFailureHandler::class // 可选，登录失败处理器配置
                ],
                'opaque_token' => [ // 不透明令牌认证器，用于API无状态认证； 一般配合 JSON登录认证器 使用
                    'token_manager' => 'default', // 可选；不透明令牌管理器服务名称; 默认default
                ],
            ],
            'listeners' => [
                [
                    'class' => LoginAttemptLimitListener::class,
                    'params' => [
                        'prefix' => 'json-login',
                        'limit' => 2,
                        'interval' => 60,
                    ],
                ],
            ],
        ],

        'api-key' => [
            'matcher' => [
                'pattern' => '^/api-key/',
            ],
            'user_provider' => [
                'type' => 'memory', // 为了演示才使用memory类型，实际使用需要使用其它type, 用户对象也只需要实现UserInterface就可以了
                'users' => [
                    'api-key-UYv78sOva1tUalvp1M2' => [  // 实际应该是一串随机生成的字符串
                        'password' => '',
                    ],
                ],
            ],
            'authenticators' => [
                'api_key' => [
                    'api_key_field' => 'X-API-KEY',
                ],
            ],

            // 'unauthenticated_handler' => [ //可自定义处理UnauthenticatedException
            //    'type' => 'custom',
            //    'class' => CustomUnauthenticatedHandler::class, // type == custom时必填，自定义未认证处理器类名
            // ],
        ],

        'hmac' => [
            'matcher' => [
                'pattern' => '^/hmac/',
                'exclusions' => [
                    '/hmac/generateSignature', // 生成签名，用于测试
                ],
            ],

            'user_provider' => [
                'type' => 'memory', // 为了演示方便，使用memory
                'users' => [
                    'hmac-user-1' => [
                        'password' => 'KLvoaD3f9qZ3TY8w', // 密钥
                    ],
                ],
            ],

            'authenticators' => [
                'hmac' => [
                    'api_key_field' => 'X-API-KEY', // 请求头中的api key参数名; 默认X-API-KEY;
                    // 'signature_field' => 'X-SIGNATURE', // 请求头中的签名参数名
                    // 'timestamp_field' => 'X-TIMESTAMP', // 请求头中的时间戳参数名
                    // 'nonce_enabled' => true, // 是否启用随机字符串; 防止重放攻击
                    // 'nonce_field' => 'X-NONCE', // nonce_enabled==true必须; 请求头中的随机字符串参数名
                    // 'nonce_cache_prefix' => 'default', // nonce_enabled==true必须; 缓存前缀
                    // 'ttl' => 60, // 请求签名的有效期，单位秒
                    // 'algo' => 'sha256', // 签名算法
                    // 'secret_encrypto_enabled' => false, // 是否启用密钥加密
                    // 'secret_encryptor' => [ // secret_encrypto_enabled==true必须; 加密器配置
                    //     'type' => 'default', // 支持default, custom
                    //     'params' => [
                    //         'algo' => 'AES-256-CBC', // type==default必须; 加密算法
                    //         'key' => 'secret-key', // type==default必须; 加密密钥
                    //     ]
                    // ]
                ],
            ],
        ],

        'jwt' => [
            'matcher' => [
                'pattern' => '^/jwt/',
            ],

            'user_provider' => [
                'type' => 'memory',
                'users' => [
                    'user1' => [
                        'password' => '$2y$10$EFSGHFCGlui7PDCztM9O9.L4Lk4Oy/uyenm1EvMgmz5oC0JKoAov2', // 123456
                    ],
                ],
            ],

            'authenticators' => [
                'json_login' => [
                    'check_path' => '/jwt/check-login',
                    'success_handler' => [
                        'class' => JWTSuccessHandler::class,
                        'params' => [
                            'jwt_manager' => 'default',
                        ],
                    ],
                ],
                'jwt' => [
                    'jwt_manager' => 'default',
                ],
            ],
        ],
    ],

    'services' => [
        'jwt_managers' => [
            'default' => [
                'secret_key' => 'a8f7d9c2e1b4f6a9c3d8e2f1a7b5c9d4e6f2a1b8c7d9e3f4a6b1c5d8e7f9a2b3', // 必须; 对称算法密钥 或 非对称算法私钥
                'ttl' => 60 * 2, // 可选；Access Token 有效期，单位：秒； 默认：600秒（10分钟）; 建议设置为5-10分钟; 这里是为了测试才设这么短
                'prefix' => 'default', // 可选；Refresh Token 缓存前缀，默认：default
                'refresh_token_enabled' => true, // 可选；是否启用刷新令牌功能，默认true
                'refresh_token_path' => '/jwt/refresh-token', // refresh_token_enabled==true时必需；刷新令牌请求路径，默认/jwt/refresh-token
                'logout_path' => '/jwt/logout', // refresh_token_enabled == true时必需；
            ],
        ],
    ],
];
