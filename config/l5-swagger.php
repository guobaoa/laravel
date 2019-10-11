<?php

return [
    'api' => [
        /*
        |--------------------------------------------------------------------------
        | Edit to set the api's title
        |--------------------------------------------------------------------------
        */

        'title' => '博客接口文档',
    ],

    'routes' => [
        /*
        |--------------------------------------------------------------------------
        | Route for accessing api documentation interface  用于访问api文档接口的路由
        |--------------------------------------------------------------------------
        */

        'api' => 'api/documentation',

        /*
        |--------------------------------------------------------------------------
        | Route for accessing parsed swagger annotations. 用于访问解析的swagger注释的路由
        |--------------------------------------------------------------------------
        */

        'docs' => 'docs',

        /*
        |--------------------------------------------------------------------------
        | Route for Oauth2 authentication callback. Oauth2身份验证回调的路由。
        |--------------------------------------------------------------------------
        */

        'oauth2_callback' => 'api/oauth2-callback',

        /*
        |--------------------------------------------------------------------------
        | Middleware allows to prevent unexpected access to API documentation 中间件允许防止对API文档的意外访问
        |--------------------------------------------------------------------------
         */
        'middleware' => [
            'api' => [],
            'asset' => [],
            'docs' => [],
            'oauth2_callback' => [],
        ],
    ],

    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Absolute path to location where parsed swagger annotations will be stored 将存储解析的swagger注释的位置的绝对路径
        |--------------------------------------------------------------------------
        */

        'docs' => storage_path('api-docs'),

        /*
        |--------------------------------------------------------------------------
        | File name of the generated json documentation file 生成的json文档文件的文件名
        |--------------------------------------------------------------------------
        */

        'docs_json' => 'api-docs.json',

        /*
        |--------------------------------------------------------------------------
        | File name of the generated YAML documentation file 生成的YAML文档文件的文件名
        |--------------------------------------------------------------------------
         */

        'docs_yaml' => 'api-docs.yaml',

        /*
        |--------------------------------------------------------------------------
        | Absolute path to directory containing the swagger annotations are stored. 存储到包含swagger注释的目录的绝对路径。
        |--------------------------------------------------------------------------
        */

        'annotations' => base_path('app'),

        /*
        |--------------------------------------------------------------------------
        | Absolute path to directory where to export views 导出视图的目录的绝对路径
        |--------------------------------------------------------------------------
        */

        'views' => base_path('resources/views/vendor/l5-swagger'),

        /*
        |--------------------------------------------------------------------------
        | Edit to set the api's base path 编辑以设置api的基本路径
        |--------------------------------------------------------------------------
        */

        'base' => env('L5_SWAGGER_BASE_PATH', null),

        /*
        |--------------------------------------------------------------------------
        | Absolute path to directories that you would like to exclude from swagger generation 要从swagger生成中排除的目录的绝对路径
        |--------------------------------------------------------------------------
        */
		
        'excludes' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | API security definitions. Will be generated into documentation file. API安全定义。将生成文档文件。
    |--------------------------------------------------------------------------
    */
    'security' => [
        /*
        |--------------------------------------------------------------------------
        | Examples of Security definitions
        |--------------------------------------------------------------------------
        */
        /*
        'api_key_security_example' => [ // Unique name of security
            'type' => 'apiKey', // The type of the security scheme. Valid values are "basic", "apiKey" or "oauth2". 安全方案的类型。有效值是“basic”、“apiKey”或“oauth2”。
            'description' => 'A short description for security scheme', //安全方案的简短描述
            'name' => 'api_key', // The name of the header or query parameter to be used. 要使用的标题或查询参数的名称
            'in' => 'header', // The location of the API key. Valid values are "query" or "header". API密钥的位置。有效值是“查询”或“标头”。
        ],
        'oauth2_security_example' => [ // Unique name of security 安全的唯一名称
            'type' => 'oauth2', // The type of the security scheme. Valid values are "basic", "apiKey" or "oauth2".
            'description' => 'A short description for oauth2 security scheme.',
            'flow' => 'implicit', // The flow used by the OAuth2 security scheme. Valid values are "implicit", "password", "application" or "accessCode".
            'authorizationUrl' => 'http://example.com/auth', // The authorization URL to be used for (implicit/accessCode)
            //'tokenUrl' => 'http://example.com/auth' // The authorization URL to be used for (password/application/accessCode)
            'scopes' => [
                'read:projects' => 'read your projects',
                'write:projects' => 'modify projects in your account',
            ]
        ],
        */

        /* Open API 3.0 support
        'passport' => [ // Unique name of security
            'type' => 'oauth2', // The type of the security scheme. Valid values are "basic", "apiKey" or "oauth2".
            'description' => 'Laravel passport oauth2 security.',
            'in' => 'header',
            'scheme' => 'https',
            'flows' => [
                "password" => [
                    "authorizationUrl" => config('app.url') . '/oauth/authorize',
                    "tokenUrl" => config('app.url') . '/oauth/token',
                    "refreshUrl" => config('app.url') . '/token/refresh',
                    "scopes" => []
                ],
            ],
        ],
        */
    ],

    /*
    |--------------------------------------------------------------------------
    | Turn this off to remove swagger generation on production 关闭此选项以消除生产上的不稳定因素
    |--------------------------------------------------------------------------
    */

    'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', true),

    /*
    |--------------------------------------------------------------------------
    | Turn this on to generate a copy of documentation in yaml format 打开此选项以生成yaml格式的文档副本
    |--------------------------------------------------------------------------
     */

    'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),

    /*
    |--------------------------------------------------------------------------
    | Edit to set the swagger version number 编辑设置swagger版本号
    |--------------------------------------------------------------------------
    */

    'swagger_version' => env('SWAGGER_VERSION', '3.0'),

    /*
    |--------------------------------------------------------------------------
    | Edit to trust the proxy's ip address - needed for AWS Load Balancer 编辑以信任代理的ip地址- AWS负载均衡器所需要的
    |--------------------------------------------------------------------------
    */

    'proxy' => false,

    /*
    |--------------------------------------------------------------------------
    | Configs plugin allows to fetch external configs instead of passing them to SwaggerUIBundle.
    | See more at: https://github.com/swagger-api/swagger-ui#configs-plugin
    |--------------------------------------------------------------------------
    */

    'additional_config_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Apply a sort to the operation list of each API. It can be 'alpha' (sort by paths alphanumerically),
    | 'method' (sort by HTTP method).
    | Default is the order returned by the server unchanged.
    |--------------------------------------------------------------------------
    */

    'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),

    /*
    |--------------------------------------------------------------------------
    | Uncomment to pass the validatorUrl parameter to SwaggerUi init on the JS
    | side.  A null value here disables validation.
    |--------------------------------------------------------------------------
    */

    'validator_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Uncomment to add constants which can be used in annotations
    |--------------------------------------------------------------------------
     */
    'constants' => [
        'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://my-default-host.com'),
    ],
];
