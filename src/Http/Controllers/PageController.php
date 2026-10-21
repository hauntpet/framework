<?php

namespace Haunt\Framework\Http\Controllers;

use LumiteStudios\Common\Extend\Controller;

class PageController extends Controller
{
    public function __invoke(string $page = '')
    {
        dd($page);
    }
}
