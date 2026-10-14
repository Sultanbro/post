#!/bin/sh
set -eu
if [ "${APP_ENV:-}" != local ]; then
    echo "This entry point is only for the local demo." >&2
    exit 1
fi
export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
php artisan config:clear
php artisan migrate --force
php artisan db:seed --class=DemoSeeder --force
php -r '
$url = "http://keycloak:8080/realms/post-demo/.well-known/openid-configuration";
for ($i = 0; $i < 90; $i++) {
    if (@file_get_contents($url, false, stream_context_create(["http" => ["timeout" => 2]])) !== false) exit(0);
    sleep(2);
}
fwrite(STDERR, "Local Keycloak did not become ready.\n");
exit(1);
'
exec php artisan serve --host=0.0.0.0 --port=8000
