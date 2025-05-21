<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    public function __invoke(): RedirectResponse|View
    {
        return redirect()->route('dhtt.home');
    }
}
