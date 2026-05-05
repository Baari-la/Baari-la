<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Inertia\Inertia;  
use App\Models\Company;             // UNTUK MENGAMBIL DATA PERUSAHAAN
use Illuminate\Support\Facades\DB;  // UNTUK MENGAMBIL DATA REQUEST
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
{
    return Inertia::render('Admin/Dashboard', [
        'stats' => [
            'total_companies' => Company::count(),
            'gold_members' => Company::where('membership_type', 'gold_member')->count(),
            'premium_requests' => DB::table('premium_requests')->where('status', 'pending')->count(),
        ],
        'recent_companies' => Company::latest()->take(5)->get(),
        'pending_requests' => DB::table('premium_requests')
            ->join('users', 'premium_requests.user_id', '=', 'users.id')
            ->select('premium_requests.*', 'users.name as user_name')
            ->where('status', 'pending')
            ->get()
    ]);
}

}