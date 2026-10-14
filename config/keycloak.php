<?php

return [
    'token_url' => env('KEYCLOAK_TOKEN_URL'),
    'userinfo_url' => env('KEYCLOAK_USERINFO_URL'),
    'users_url' => env('KEYCLOAK_USERS_URL'),
    'client_id' => env('KEYCLOAK_CLIENT_ID', 'rest-client'),
    'client_secret' => env('KEYCLOAK_CLIENT_SECRET'),
    'admin_username' => env('KEYCLOAK_ADMIN_USERNAME'),
    'admin_password' => env('KEYCLOAK_ADMIN_PASSWORD'),
];
