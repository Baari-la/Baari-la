<?php
namespace App\Http\Controllers; // Tambahkan namespace ini

use Illuminate\Http\Request;
use Inertia\Inertia; // Tambahkan ini untuk merender halaman
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk akses database


class LandingPageController extends Controller
{
public function index()
{
$topStocks = \DB::table('companies')
->where('stock_qty', '>', 0)
->selectRaw('stock_ready_caption as product_name, SUM(stock_qty) as total_qty, stock_unit as unit, COUNT(id) as
total_suppliers')
->groupBy('product_name', 'unit')
->orderBy('total_qty', 'desc')
->take(4) // Ambil 4 teratas saja untuk Landing Page
->get();

return Inertia::render('Home', [
'topStocks' => $topStocks,
// props lainnya
]);
}
}