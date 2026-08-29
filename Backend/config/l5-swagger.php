<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Palm API Documentation',
                'description' => 'RESTful API for Palm Application - Modular Monolith Architecture with CQRS Pattern',
                'version' => '1.0.0',
                'contact' => [
                    'name' => 'Palm Development Team',
                    'email' => 'dev@palm.com',
                ],
                'license' => [
                    'name' => 'MIT',
                    'url' => 'https://opensource.org/licenses/MIT',
                ],
            ],
            'routes' => [
                /*
                |--------------------------------------------------------------------------
                | Route for accessing api documentation interface
                |--------------------------------------------------------------------------
                */
                'api' => 'api/documentation',
                
                /*
                |--------------------------------------------------------------------------
                | Route for accessing OAuth2 callback
                |--------------------------------------------------------------------------
                */
                'oauth2_callback' => 'api/oauth2-callback',

                /*
                |--------------------------------------------------------------------------
                | Middleware for protecting the documentation
                |--------------------------------------------------------------------------
                */
                'middleware' => ['web'],
            ],
            'paths' => [
                /*
                |--------------------------------------------------------------------------
                | Path to the directory where the annotation files are located
                |--------------------------------------------------------------------------
                */
                'annotations' => storage_path('api'),

                /*
                |--------------------------------------------------------------------------
                | Absolute path to the json file that will be used to generate swagger
                |--------------------------------------------------------------------------
                */
                'json' => storage_path('api-docs/api-docs.json'),

                /*
                |--------------------------------------------------------------------------
                | Absolute path to the yaml file that will be used to generate swagger
                |--------------------------------------------------------------------------
                */
                'yaml' => base_path('openapi.yaml'),
            ],
            /*
            |--------------------------------------------------------------------------
            | UI to render the swagger documentation
            |--------------------------------------------------------------------------
            */
            'ui' => [
                /*
                |--------------------------------------------------------------------------
                | UI to render (swagger-ui or redoc)
                |--------------------------------------------------------------------------
                */
                'render' => env('L5_SWAGGER_UI_RENDER', 'swagger-ui'),

                /*
                |--------------------------------------------------------------------------
                | Additional parameters for swagger-ui
                |--------------------------------------------------------------------------
                */
                'additional_config_url' => null,
                'validator_url' => null,
                'deep_linking' => true,
                'display_operation_id' => false,
                'default_models_expand_depth' => 1,
                'default_model_expand_depth' => 1,
                'default_model_rendering' => 'example',
                'display_request_duration' => false,
                'doc_expansion' => 'list',
                'filter' => true,
                'max_displayed_tags' => 0,
                'show_extensions' => false,
                'show_common_extensions' => false,
                'try_it_out_enabled' => true,
                'try_it_out_url' => null,
                'persist_authorization' => false,
            ],
            /*
            |--------------------------------------------------------------------------
            | Constants that can be used in annotations
            |--------------------------------------------------------------------------
            */
            'constants' => [
                'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://localhost:8000'),
            ],
            /*
            |--------------------------------------------------------------------------
            | Generate for the OpenAPI specification
            |--------------------------------------------------------------------------
            */
            'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
            'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),

            /*
            |--------------------------------------------------------------------------
            | Proxy settings
            |--------------------------------------------------------------------------
            */
            'proxy' => false,
            'additional_config_url' => null,
            'operations_sort' => null,
            'validator_url' => null,
        ],
    ],
];
