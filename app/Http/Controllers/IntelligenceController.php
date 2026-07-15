<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class IntelligenceController extends Controller
{
    public function executive(): Response
    {
        return Inertia::render(
            'Intelligence/Executive/Index'
        );
    }

    public function company(): Response
    {
        return Inertia::render(
            'Intelligence/Company/Index'
        );
    }

    public function knowledgeGraph(): Response
    {
        return Inertia::render(
            'Intelligence/KnowledgeGraph/Index'
        );
    }

    public function masterData(): Response
    {
        return Inertia::render(
            'Intelligence/MasterData/Index'
        );
    }

    public function visualization(): Response
    {
        return Inertia::render(
            'Intelligence/Visualization/Index'
        );
    }
}