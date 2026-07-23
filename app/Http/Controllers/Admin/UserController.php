<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
{
    return Inertia::render(
        'Admin/Users/Index',
        [
            'users' => User::with('company')
                ->latest()
                ->take(20)
                ->get(),

            'stats' => [

                'total_users' =>
                    User::count(),

                'premium_users' =>
                    User::where(
                        'is_premium',
                        true
                    )->count(),

                'company_owners' =>
                    User::whereNotNull(
                        'company_id'
                    )->count(),

                'admins' =>
                    User::where(
                        'role',
                        'admin'
                    )->count(),
            ],
        ]
    );
}

    public function admins()
{
    return Inertia::render(
        'Admin/Users/Admins',
        [
            'admins' => User::whereIn(
                'role',
                [
                    'admin',
                    'super_admin',
                ]
            )
            ->latest()
            ->get(),
        ]
    );
}

    public function premium()
{
    return Inertia::render(
        'Admin/Users/PremiumUsers',
        [
            'users' => User::with('company')
                ->where(
                    'is_premium',
                    true
                )
                ->latest()
                ->get(),
        ]
    );
}

    public function companyOwners()
{
    return Inertia::render(
        'Admin/Users/CompanyOwners',
        [
            'users' => User::with(
                'company'
            )
            ->whereNotNull(
                'company_id'
            )
            ->latest()
            ->get(),
        ]
    );
}

    public function pendingVerification()
{
    return Inertia::render(
        'Admin/Users/PendingVerification',
        [
            'users' => User::with('company')
                ->where(function ($query) {

                    $query
                        ->whereNull(
                            'email_verified_at'
                        )

                        ->orWhereHas(
                            'company',
                            function ($company) {

                                $company->where(
                                    'status_verifikasi',
                                    'pending'
                                );
                            }
                        );

                })
                ->latest()
                ->get(),
        ]
    );
}

    public function activityLogs()
{
    return Inertia::render(
        'Admin/Users/ActivityLogs',
        [
            'logs' => AuditLog::with([
                'user',
                'company',
            ])
            ->latest()
            ->take(100)
            ->get(),
        ]
    );
}
}