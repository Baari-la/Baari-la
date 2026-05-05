<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Digestex V2 - Industrial Intelligence Platform</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link href="https://cloudflare.com" rel="stylesheet">
    <style>
        body { background-color: #0a192f; color: white; font-family: 'Inter', sans-serif; }
        .bg-navy-light { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); }
        .text-gold { color: #ffc107 !important; }
        .oswald { font-family: 'Oswald', sans-serif; }
    </style>
@extends('layouts.app')

@section('content')


    <div class="container py-5">
        <!-- BIG NUMBERS ROW -->
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card bg-navy-light rounded-4 p-4 border-start border-warning border-5 shadow">
                    <h6 class="oswald text-warning mb-1">NATIONAL EXPORT VALUE</h6>
                    <h2 class="display-4 fw-bold mb-0">$11.9 <small class="fs-4 text-white-50">BILLION</small></h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-navy-light rounded-4 p-4 border-start border-danger border-5 shadow">
                    <h6 class="oswald text-danger mb-1">LIVE COTTON PRICE (NY/ICE)</h6>
                    <h2 class="display-4 fw-bold mb-0">71.31 <small class="fs-4 text-white-50">USD/LB</small></h2>
                </div>
            </div>
        </div>

        <!-- MAIN DASHBOARD AREA -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card bg-navy-light rounded-4 p-4 mb-4">
                    <h5 class="oswald fw-bold text-uppercase mb-4"><i class="fas fa-chart-line me-2 text-warning"></i> Trade Performance Analysis</h5>
                    <div class="bg-dark rounded-4 d-flex justify-content-center align-items-center" style="height: 300px; border: 1px dashed rgba(255,255,255,0.2);">
                        <p class="text-white-50 small italic">AI Visualization Engine Running...</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card bg-navy-light rounded-4 p-4">
                    <h5 class="oswald fw-bold text-uppercase mb-3"><i class="fas fa-robot me-2 text-warning"></i> AI Prediction 2026</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex justify-content-between">
                            <span>01. UNITED STATES</span>
                            <span class="text-gold fw-bold">$4.2 B</span>
                        </li>
                        <li class="opacity-25 mb-2 d-flex justify-content-between" style="filter: blur(2px);">
                            <span>02. CONFIDENTIAL</span>
                            <span>$X.X B</span>
                        </li>
                        <li class="opacity-25 mb-2 d-flex justify-content-between" style="filter: blur(2px);">
                            <span>03. CONFIDENTIAL</span>
                            <span>$X.X B</span>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-warning w-100 btn-sm mt-3 fw-bold rounded-pill">UPGRADE TO PREMIUM</a>
                </div>
            </div>

<a href="{{ url('/') }}" class="btn btn-outline-light btn-sm rounded-pill px-4">
        <i class="fas fa-home me-2"></i> BACK TO HOME
    </a>

        </div>
    </div>
@endsection
