<?php

declare(strict_types=1);

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use App\Services\Trade\ThreadTradeIntelligenceService;
use Inertia\Inertia;
use Inertia\Response;

class ThreadIntelligenceController extends Controller
{
    /**
     * Display Thread Trade Intelligence.
     *
     * The page reads from:
     *
     * Cache
     *   ↓
     * Persistent validated snapshot
     *   ↓
     * ThreadTradeIntelligenceService
     *
     * No heavy aggregation is performed during the user request.
     */
    public function index(
        ThreadTradeIntelligenceService $service
    ): Response {
        $thread = $service->get();

        return Inertia::render(
            'Trade/ThreadIntelligence',
            [
                'thread' => $thread,

                /*
                |--------------------------------------------------------------------------
                | UI Metadata
                |--------------------------------------------------------------------------
                |
                | Keep labels here lightweight.
                | Actual trade data comes from the snapshot.
                |
                */

                'page' => [
                    'title_en' =>
                        'Thread Intelligence',

                    'title_id' =>
                        'Thread Intelligence',

                    'subtitle_en' =>
                        'Sewing Thread Trade Intelligence',

                    'subtitle_id' =>
                        'Trade Intelligence Benang Jahit',

                    'sector' =>
                        'thread',

                    'bilingual' =>
                        true,
                ],
            ]
        );
    }
}