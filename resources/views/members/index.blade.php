<!DOCTYPE html>
<html lang="id">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digestex V2 - Member Intelligence</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
    /* Desain untuk Layar (Tetap Mewah) */
    .d-print-block { display: none; }

    @media print {
        /* Sembunyikan elemen yang tidak perlu di kertas */
        .btn, .nav, footer, .badge-danger { display: none !important; }
        
        /* Tampilkan Kop Surat */
        .d-print-block { display: block !important; }
        
        /* Ubah Background Navy menjadi Putih agar hemat tinta & bersih */
        body { 
            background-color: white !important; 
            color: black !important; 
            padding: 0 !important;
        }
        
        .card-luxury { 
            background: transparent !important; 
            border: none !important; 
            box-shadow: none !important;
            padding: 0 !important;
        }

        .table { color: black !important; border-color: #dee2e6 !important; }
        .table-light { background-color: #f8f9fa !important; color: black !important; }
        .text-gold, .text-white, .text-white-50 { color: black !important; }
        
        /* Tambahkan tanda tangan di bawah laporan */
        body::after {
            content: "Approved by Digestex Intelligence System | Generated on: <?= date('d M Y H:i') ?>";
            display: block;
            margin-top: 50px;
            font-size: 10px;
            text-align: right;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    }
</style>

@extends('layouts.app')

@section('content')

<div class="container py-5">
<!-- Tambahan Kop-->
 <!-- KOP SURAT (Hanya muncul saat PRINT) -->
<div class="d-none d-print-block mb-4">
    <div class="row align-items-center border-bottom border-dark pb-3">
        <div class="col-2">
           <img src="{{ asset('images/logo_api_digestex2.png') }}" style="width: 120px;">
        </div>
        <div class="col-8 text-center">
            <h4 class="fw-bold mb-0">DIGESTEX MEDIA INTELLIGENCE</h4>
            <p class="small mb-0">Indonesian Textile Association (API) Jakarta Strategic Partner</p>
            <p class="small mb-0 italic text-muted">Industrial Member Verification Report - Confidential</p>
        </div>
        <div class="col-2 text-end">
            <h6 class="fw-bold text-danger">V2.0</h6>
        </div>
    </div>
</div>


<!-- Batas tambahan kop -->
<div class="text-center mb-5">
        <h2 class="oswald fw-bold text-uppercase text-gold letter-spacing-2">Industrial Member <span class="text-white">Intelligence</span></h2>
        <p class="text-white-50">Digestex V2 Enterprise Infrastructure - Laravel 12 & MySQL</p>
    
    <!-- Tombol cetak pdf -->
     <button onclick="window.print()" class="btn btn-danger mb-3 shadow">
    <i class="fas fa-file-pdf"></i> GENERATE INSTANT REPORT
</button>
     <!-- Batas cetak tombol pdf -->    
    </div>


    <div class="card card-luxury p-4 shadow-lg animate__animated animate__fadeIn">
    
    
    <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light oswald">
                    <tr>
                        <th class="py-3">ID</th>
                        <th class="py-3">COMPANY NAME</th>
                        <th class="py-3">EMAIL ADDRESS</th>
                        <th class="py-3 text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $m)
                    <tr>
                        <td class="opacity-50 small">{{ $m->id_perusahaan }}</td>
                        <td class="fw-bold text-white">{{ $m->nama_perusahaan }}</td>
                        <td class="text-white-50 small">{{ $m->email }}</td>
                        <td class="text-center"><span class="badge bg-warning text-dark rounded-pill px-3">PREMIUM MEMBER</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
