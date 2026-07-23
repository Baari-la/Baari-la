<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;
use App\Models\SystemSetting;

class SettingsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    public function index(): Response
    {
        return Inertia::render(
            'Admin/Settings/Index'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | General Settings
    |--------------------------------------------------------------------------
    */

    public function general(): Response
    {
        return Inertia::render(
            'Admin/Settings/GeneralSettings',
            [
                'settings' => $this->getGroup(
                    'general'
                ),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Membership Settings
    |--------------------------------------------------------------------------
    */

    public function membership(): Response
    {
        return Inertia::render(
            'Admin/Settings/MembershipSettings',
            [
                'settings' => $this->getGroup(
                    'membership'
                ),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Gateway
    |--------------------------------------------------------------------------
    */

    public function paymentGateway(): Response
    {
        return Inertia::render(
            'Admin/Settings/PaymentGatewaySettings',
            [
                'settings' => $this->getGroup(
                    'payment'
                ),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Email
    |--------------------------------------------------------------------------
    */

    public function email(): Response
    {
        return Inertia::render(
            'Admin/Settings/EmailSettings',
            [
                'settings' => $this->getGroup(
                    'email'
                ),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    */

    public function localization(): Response
    {
        return Inertia::render(
            'Admin/Settings/LocalizationSettings',
            [
                'settings' => $this->getGroup(
                    'localization'
                ),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    */

    public function security(): Response
    {
        return Inertia::render(
            'Admin/Settings/SecuritySettings',
            [
                'settings' => $this->getGroup(
                    'security'
                ),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    */

    public function storage(): Response
    {
        return Inertia::render(
            'Admin/Settings/StorageSettings',
            [
                'settings' => $this->getGroup(
                    'storage'
                ),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    */

    public function queue(): Response
    {
        return Inertia::render(
            'Admin/Settings/QueueManagement',
            [
                'stats' => [
                    'driver'     => config(
                        'queue.default'
                    ),

                    'workers'    => 1,

                    'retry'      => 3,

                    'pending'    => 0,

                    'processing' => 0,

                    'completed'  => 0,

                    'failed'     => 0,
                ],

                'failedJobs' => [],
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | System Health
    |--------------------------------------------------------------------------
    */

    public function systemHealth(): Response
    {
        return Inertia::render(
            'Admin/Settings/SystemHealth',
            [
                'health' => [
                    'php' => PHP_VERSION,

                    'laravel' => app()->version(),

                    'environment' => app()->environment(),

                    'debug' => config(
                        'app.debug'
                    ),

                    'queue' => config(
                        'queue.default'
                    ),

                    'cache' => config(
                        'cache.default'
                    ),
                ],
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    private function getGroup(
        string $group
    ): array {

        if (
            ! class_exists(
                SystemSetting::class
            )
        ) {
            return [];
        }

        return SystemSetting::where(
            'group',
            $group
        )
            ->pluck(
                'value',
                'key'
            )
            ->toArray();
    }
}