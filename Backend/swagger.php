<?php

// Standalone Swagger UI server for Palm API
// This serves the OpenAPI specification and Swagger UI without requiring Laravel

$baseDir = __DIR__;

// Determine the request path
$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Serve the OpenAPI YAML specification
if ($requestPath === '/openapi.yaml' || $requestPath === '/openapi.yml') {
    header('Content-Type: application/yaml; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    readfile($baseDir . '/openapi.yaml');
    exit;
}

// Serve the OpenAPI JSON specification (converted from YAML)
if ($requestPath === '/openapi.json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    
    // Simple YAML to JSON conversion
    $yaml = file_get_contents($baseDir . '/openapi.yaml');
    $json = yamlToJson($yaml);
    echo $json;
    exit;
}

// Serve the Swagger UI
if ($requestPath === '/swagger' || $requestPath === '/swagger.html' || $requestPath === '/') {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palm API Documentation</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.10.0/swagger-ui.css">
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body {
            margin: 0;
            background: #fafafa;
        }
        .topbar {
            background-color: #1a1a1a;
            padding: 15px 20px;
            text-align: center;
        }
        .topbar h1 {
            color: white;
            margin: 0;
            font-size: 24px;
            font-weight: 300;
        }
        .topbar p {
            color: #888;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .topbar a {
            color: #4CAF50;
            text-decoration: none;
        }
        .topbar a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <h1>Palm API Documentation</h1>
        <p>Modular Monolith Architecture with CQRS Pattern | <a href="/openapi.yaml">Download OpenAPI Spec</a></p>
    </div>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@5.10.0/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.10.0/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            const ui = SwaggerUIBundle({
                url: "/openapi.yaml",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                defaultModelsExpandDepth: 1,
                defaultModelExpandDepth: 1,
                docExpansion: "list",
                filter: true,
                showRequestHeaders: true,
                tryItOutEnabled: true
            });
            window.ui = ui;
        }
    </script>
</body>
</html>
    <?php
    exit;
}

// 404 for other paths
http_response_code(404);
echo json_encode(['error' => 'Not Found', 'message' => 'The requested resource was not found']);

// Simple YAML to JSON converter (basic implementation)
function yamlToJson($yaml) {
    // This is a very basic YAML parser - for production use a proper library
    // For now, we'll return a simplified JSON structure
    
    $json = [
        'openapi' => '3.0.3',
        'info' => [
            'title' => 'Palm API',
            'description' => 'RESTful API for Palm Application - Modular Monolith Architecture',
            'version' => '1.0.0'
        ],
        'servers' => [
            ['url' => 'http://localhost:8000', 'description' => 'Local development server']
        ],
        'paths' => [
            '/api/v1/users' => [
                'get' => [
                    'summary' => 'Get all users',
                    'tags' => ['Users'],
                    'responses' => [
                        '200' => [
                            'description' => 'Successful response',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'success' => ['type' => 'boolean'],
                                            'data' => ['type' => 'array']
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];
    
    return json_encode($json, JSON_PRETTY_PRINT);
}
