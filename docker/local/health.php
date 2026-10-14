<?php
$headers = @get_headers('http://127.0.0.1:8000/api/posts', false,
    stream_context_create(['http' => ['timeout' => 3]]));
exit($headers && strpos($headers[0], ' 401 ') !== false ? 0 : 1);
