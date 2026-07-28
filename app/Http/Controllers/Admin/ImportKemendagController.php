<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Trade\TradeStatisticsImportService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ImportKemendagController extends Controller
{
    /**
     * Display Import Page
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Trade/ImportKemendag');
    }

    /**
     * Import Trade Statistics Workbook
     */
    public function store(
        Request $request,
        TradeStatisticsImportService $importService
    ) {
        $request->validate([
            'file_excel' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:51200', // 50 MB
            ],
        ]);

        try {
        
            $summary = $importService->import(
                $request->file('file_excel')->getRealPath()
            );

            return back()->with([
                'success' => 'Trade Statistics berhasil diimport.',
                'summary' => $summary,
            ]);

        } catch (Throwable $e) {

            report($e);

            return back()->withErrors([
                'file_excel' => $e->getMessage(),
            ]);
        }
    }
}