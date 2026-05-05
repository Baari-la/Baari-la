<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- 1. SEO Dasar -->
    <title inertia>{{ config('app.name', 'DigestexGlobal') }} | Indonesia Textile Industrial Intelligence</title>
    <meta name="description"
        content="The global gateway to Indonesia's textile intelligence. 8-Digit HS Code analytics, garment export radar, and verified industry directory.">
    <meta name="keywords"
        content="Indonesia Textile Data, Garment Export Radar, HS Code 61 62, API Jakarta, Textile Intelligence">
    <meta name="author" content="DigestexGlobal">
    <meta name="robots" content="index, follow">

    <!-- 2. Open Graph / Facebook (Agar saat share ke WA/FB muncul gambar & deskripsi) -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="DigestexGlobal | Indonesia Textile Industrial Intelligence">
    <meta property="og:description"
        content="The global gateway to Indonesia's textile intelligence. 8-Digit HS Code analytics & industry directory.">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}"> <!-- Pastikan file gambar ini ada -->

    <!-- 3. Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="DigestexGlobal | Indonesia Textile Intelligence">
    <meta name="twitter:description" content="Analyze 8-Digit HS Code and Garment Export Radar in Indonesia.">
    <meta name="twitter:image" content="{{ asset('images/og-image.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />




    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">




    <!-- Scripts & Inertia -->
    @routes
    @viteReactRefresh
    @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>