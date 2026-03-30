<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Taku</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body { margin: 0; font-family: Arial; }
        .container { width: 90%; margin: auto; }
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
