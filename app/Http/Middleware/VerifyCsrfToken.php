<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'bangun-ruang/store',
        'bangun-ruang/update/*',
        'bangun-ruang/delete/*',
        // tambahkan route lain yang ingin dikecualikan CSRF
    ];
}
