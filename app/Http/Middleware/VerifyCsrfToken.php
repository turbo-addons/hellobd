<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'install/complete', // Installation completion (protected by redirect.if.installed middleware)
        'api/frontend/submit-vote', // Public vote submission API
        'api/frontend/subscribe', // Public newsletter subscription API
    ];
}
