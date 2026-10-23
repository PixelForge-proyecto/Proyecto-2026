<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @yield('title', 'Translogística')
    </title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon-translogistica.png') }}">

    <link rel="apple-touch-icon" href="{{ asset('images/favicon-translogistica.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @yield('content')
</body>

</html>
