<?php

use App\FailureHandler\APIKeyFailureHandler;
use App\IPResolver;
use GaaraHyperf\Authenticator\OpaqueTokenResponseHandler;
use GaaraHyperf\EventListener\LoginAttemptLimitListener;
use GaaraHyperf\EventListener\LoginRateLimitListener;

return [
    'guards' => [
        'from-auth-example' => [
            'matcher' => [
                'pattern' => '^/form-auth/',
                'logout_path' => '/form-auth/logout',
                'exclusions' => [
                    '/form-auth/login', // form认证需要排除登录页面
                ],
            ],

            'user_provider' => [
                'type' => 'memory', // 支持 memory, model, custom
                'users' => [ // type == memory时必填，内存用户列表
                    'admin' => [
                        'password' => '$2y$10$Eaf.zDYlBYKk9sxwrpxox.L6YKz2Ef3ssikoNtgn2zwQcjoirW22y',
                    ],
                ],
            ],

            'authenticators' => [
                'form_login' => [
                    'check_path' => '/form-auth/check-login', // 登录表单提交路径
                    'target_path' => '/form-auth/index', // 登录成功跳转路径
                    'failure_path' => '/form-auth/login', // 登录失败跳转路径
                    'redirect_enabled' => true, // 是否启用登录成功后的重定向
                    // 'redirect_param' => 'redirect_to', // 重定向目标路径参数名
                    // 'username_param' => 'username', // 用户名参数名
                    // 'password_param' => 'password', // 密码参数名
                    // 'error_message' => '用户名或密码错误', // 登录失败错误消息; 支持字符串或回调函数; 回调函数参数为 AuthenticationException 实例
                    // 'csrf_enabled' => true, // 是否启用CSRF保护
                    // 'csrf_id' => 'authenticate', // CSRF令牌ID
                    // 'csrf_param' => '_csrf_token', // CSRF令牌参数名
                    // 'csrf_token_manager' => 'default', // CSRF令牌管理器服务名称
                ],
            ],

            'token_storage' => [
                'type' => 'session', // form认证使用session存储
                'prefix' => 'form-auth',
            ],

            'unauthenticated_handler' => [
                'type' => 'redirect', // form认证一般使用重定向处理未认证请求
                'target_path' => '/form-auth/login',
                // 'redirect_enabled' => true,
                // 'redirect_param' => 'redirect_to',
            ],
        ],

        'json-auth-example' => [
            'matcher' => [
                'pattern' => '^/json-auth/',
                'logout_path' => '/json-auth/logout',
            ],

            'user_provider' => [
                'type' => 'memory',
                'users' => [
                    'admin' => [
                        'password' => '$2y$10$Eaf.zDYlBYKk9sxwrpxox.L6YKz2Ef3ssikoNtgn2zwQcjoirW22y',
                    ],
                ],
            ],

            'authenticators' => [
                'json_login' => [
                    'check_path' => '/json-auth/check_login', // JSON登录请求路径
                    // 'username_param' => 'username', // 用户名字段名
                    // 'password_param' => 'password', // 密码字段名
                    'success_handler' => [ // 可选，登录成功处理器配置; 无状态认证时一般都需要配置, 用于生成access token返回给客户端
                        'class' => OpaqueTokenResponseHandler::class,
                        'params' => [
                            'token_manager' => 'default',
                            'response_template' => '{ "code": 0, "msg": "success", "data": { "access_token": "#ACCESS_TOKEN#"} }',
                        ],
                    ],
                    // 'failure_handler' => CustomFailureHandler::class // 可选，登录失败处理器配置
                ],
                'opaque_token' => [ // 不透明令牌认证器，用于API无状态认证； 一般配合 JSON登录认证器 使用
                    // 'token_manager' => 'default', // 可选；不透明令牌管理器服务名称; 默认default
                    // 'token_extractor' => [
                    //     'type' => 'header', // 支持 header, cookie, custom; 默认header
                    //     'param_name' => 'Authorization', // type == header时可选，默认Authorization; type == cookie时可选，默认access_token
                    //     'param_type' => 'Bearer', // type == header时可选，默认Bearer
                    // ],
                ],
            ],
            'listeners' => [
                [
                    'class' => LoginAttemptLimitListener::class,
                    'params' => [
                        'type' => 'sliding_window', // 限流器类型，支持 token_bucket, sliding_window, fixed_window
                        'options' => [
                            'limit' => 2,
                            'interval' => 60,
                        ]
                    ]
                ],
            ]
        ],

        'api-key-example' => [
            'matcher' => [
                'pattern' => '^/api-key-auth/',
            ],

            'user_provider' => [
                'type' => 'memory', // 为了演示方便，使用memory; 实际需要自定义一个实现了UserInterface的用户模型类, 不需要密码
                'users' => [
                    'api-key-UYv78sOva1tUalvp1M2' => [ // 实际应该是一串随机生成的字符串
                        'password' => ''
                    ],
                ],
            ],

            'authenticators' => [
                'api_key' => [
                    'api_key_param' => 'X-API-KEY',
                ],
            ],
        ],

        'hmac-example' => [
            'matcher' => [
                'pattern' => '^/hmac-auth/',
                'exclusions' => [
                    '/hmac-auth/generateSignature'
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
                    'api_key_param' => 'X-API-KEY', // 请求头中的api key参数名; 默认X-API-KEY; 
                    //     'signature_param' => 'X-SIGNATURE', // 请求头中的签名参数名
                    //     'timestamp_param' => 'X-TIMESTAMP', // 请求头中的时间戳参数名
                    //     'nonce_enabled' => true, // 是否启用随机字符串; 防止重放攻击
                    //     'nonce_param' => 'X-NONCE', // nonce_enabled==true必须; 请求头中的随机字符串参数名 
                    //     'nonce_cache_prefix' => 'default', // nonce_enabled==true必须; 缓存前缀
                    //     'ttl' => 60, // 请求签名的有效期，单位秒
                    //     'algo' => 'sha256', // 签名算法
                    //     'secret_encrypto_enabled' => false, // 是否启用密钥加密
                    //     'secret_encryptor' => [ // secret_encrypto_enabled==true必须; 加密器配置
                    //         'type' => 'default', // 支持default, custom
                    //         'params' => [
                    //             'algo' => 'AES-256-CBC', // type==default必须; 加密算法
                    //             'key' => 'secret-key', // type==default必须; 加密密钥
                    //         ]
                    //     ]
                ],
            ],
        ],
    ],

    'services' => [
        // 'password_hashers' => [ // 密码哈希器服务配置; 内置了一个名称为default的密码哈希器服务
        //     'default' => [ // 密码哈希器服务名称
        //         'type' => 'default', // 支持 default, custom; 默认default
        //         'algo' => PASSWORD_BCRYPT, // type == default时可选，哈希算法; 默认PASSWORD_BCRYPT
        //         'class' => CustomPasswordHasher::class, // type == custom时必填，自定义密码哈希器类名
        //     ]
        // ],
        // 'csrf_token_managers' => [ // CSRF令牌管理器配置; 内置了一个名称为default的管理器(type==session)
        //     'default' => [
        //         'type' => 'session', // 支持 session, custom; 默认session
        //         'prefix' => 'default' // 存储前缀; 默认default; 多个管理器时必须配置不同的前缀
        //     ]
        // ],
        // 'opaque_token_managers' => [ // 不透明令牌管理器配置; 内置了一个名称为default的管理器(type==default)
        //     'default' => [ // 不透明令牌管理器名称; 可按实际情况为每个Guard配置不同的管理器
        //         'type' => 'default', // 支持 default, custom; 默认default
        //         'prefix' => 'admin', // 存储前缀; 默认default; 多个管理器时必须配置不同的前缀
        //         'expires_in' => 60 * 20, // token过期时间，单位秒; 必须小于等于 max_lifetime; 默认1200秒
        //         'max_lifetime' => 60 * 60 * 24, // token最大生命周期，单位秒; 默认86400秒
        //         'token_refresh' => true, // 是否启用token刷新机制; 默认true
        //         'ip_bind_enabled' => false, // 是否启用IP绑定; 默认false
        //         'user_agent_bind_enabled' => false, // 是否启用User-Agent绑定; 默认false
        //         'single_session' => true, // 是否启用单会话登录; 默认true
        //         'access_token_length' => 16, // 生成令牌长度; 默认16
        //     ]
        // ],
    ],
];
