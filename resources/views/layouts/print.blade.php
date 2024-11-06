<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}"> <!-- Laravel Mix CSS -->
    <style>
        @media print {
            .btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
