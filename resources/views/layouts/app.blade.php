<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="-oQVas1lrfhToFbwR2hZS-RWgylnNxY2lDG0V-s03j0" />

    <title>@yield('title', 'Taku — Premium Plant Marketplace Indonesia')</title>
    <meta name="description" content="@yield('meta_description', 'Taku menghadirkan tanaman premium pilihan untuk ruang modern kamu. Temukan koleksi plants berkualitas, estetik, dan mudah dirawat — dikirim ke seluruh Indonesia.')">
    <meta name="keywords" content="taku, premium plants, tanaman hias premium, jual tanaman online, tanaman indoor, tanaman estetik, plant marketplace, tanaman untuk apartemen, tanaman dekorasi rumah, beli tanaman online Indonesia">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Taku">
    <meta property="og:title" content="@yield('title', 'Taku — Premium Plant Marketplace Indonesia')">
    <meta property="og:description" content="@yield('meta_description', 'Taku menghadirkan tanaman premium pilihan untuk ruang modern kamu. Estetik, berkualitas, dikirim ke seluruh Indonesia.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-taku.jpg'))">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Taku — Premium Plant Marketplace Indonesia')">
    <meta name="twitter:description" content="@yield('meta_description', 'Tanaman premium pilihan untuk ruang modern kamu. Hanya di Taku.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-taku.jpg'))">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" sizes="32x32">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    @yield('head_extra')

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            background: #f8f6f2;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main { flex: 1; }
    </style>
</head>
<body>
    @include('components.header')
    <main>
        @yield('content')
    </main>
    @include('components.footer')
</body>
</html>

