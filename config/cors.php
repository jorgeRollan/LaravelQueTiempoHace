<?php
return [
    'paths' => ['api/*', '*','register/', 'login/', 'logout/', '/deleteCiudad'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5173', 'https://reactquetiempohace.onrender.com/'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
