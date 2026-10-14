<?php
// Exercises the real HTTP stack and the local Keycloak, without logging tokens.
$base = 'http://127.0.0.1:8000/api';
function requestApi(string $method, string $path, ?array $data = null, ?string $token = null): array
{
    global $base;
    $headers = "Accept: application/json\r\nContent-Type: application/json\r\n";
    if ($token !== null) $headers .= "Authorization: Bearer $token\r\n";
    $context = stream_context_create(['http' => [
        'method' => $method, 'header' => $headers, 'timeout' => 15,
        'ignore_errors' => true, 'content' => $data === null ? '' : json_encode($data),
    ]]);
    $body = file_get_contents($base . $path, false, $context);
    preg_match('/\s(\d{3})\s/', $http_response_header[0] ?? '', $matches);
    return [(int) ($matches[1] ?? 0), json_decode($body ?: '', true)];
}
function expectStatus(array $response, int $status, string $label): void
{
    if ($response[0] !== $status) throw new RuntimeException("$label: expected $status, received {$response[0]}");
    echo "$label: OK\n";
}
expectStatus(requestApi('GET', '/posts'), 401, 'Anonymous access denied');
$editor = requestApi('POST', '/login', ['username' => 'editor', 'password' => 'local-demo-only']);
expectStatus($editor, 200, 'Editor login through Keycloak');
$token = $editor[1]['access_token'];
$posts = requestApi('GET', '/posts', null, $token);
expectStatus($posts, 200, 'Read posts');
$created = requestApi('POST', '/posts', [
    'content' => 'Local smoke check', 'company_id' => $editor[1]['user_info']['company_id'], 'group_id' => 1,
], $token);
expectStatus($created, 201, 'Create post');
$id = $created[1]['data']['id'];
expectStatus(requestApi('PUT', '/posts/' . $id, ['content' => 'Updated smoke check'], $token), 200, 'Update post');
expectStatus(requestApi('DELETE', '/posts/' . $id, null, $token), 200, 'Delete post');
$reader = requestApi('POST', '/login', ['username' => 'reader', 'password' => 'local-demo-only']);
expectStatus($reader, 200, 'Reader login through Keycloak');
expectStatus(requestApi('POST', '/posts', ['content' => 'Denied', 'company_id' => 1, 'group_id' => 1], $reader[1]['access_token']), 403, 'Reader write denied');
expectStatus(requestApi('POST', '/logout', null, $token), 200, 'Logout');
expectStatus(requestApi('GET', '/posts', null, $token), 401, 'Logged-out token denied');
