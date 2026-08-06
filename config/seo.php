<?php

return [
    'enforce_canonical_host' => env(
        'SEO_ENFORCE_CANONICAL_HOST',
        env('APP_ENV', 'production') === 'production'
    ),
];
