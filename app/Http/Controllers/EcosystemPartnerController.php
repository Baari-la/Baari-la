<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class EcosystemPartnerController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'EcosystemPartner/Index'
        );
    }
}