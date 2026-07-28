<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DirectoryVisibilityProgramController extends Controller
{
    public function index(): Response
    {
        return Inertia::render(
            'Public/DirectoryVisibilityProgram/Index'
        );
    }
}