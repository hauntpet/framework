<?php

namespace Haunt\Framework\Http\Controllers;

use LumiteStudios\Common\Extend\Controller;

class InstallController extends Controller
{
    public function index()
    {
        $token = config('haunt-id.token');

        return view('haunt::pages.install')->with([
            'token' => $token,
        ]);
    }
}
