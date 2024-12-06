<!DOCTYPE html>  
<html lang="en">  

<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
    <title>@yield('title')</title>  
    <link rel="icon" href="{{ asset('images/Logo.png') }}">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Tambahkan ini -->  
    <meta name="keywords" content="restaurant, food, service">  
    <meta name="description" content="Selamat datang di Login Admin Salby Chicken Smash. Ini adalah halaman bagi admin untuk mengelola layanan dan produk kami.">  
    <meta name="author" content="Salby Chicken Smash">  
</head>  

<body>  
    @yield('content')  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  
    <script>  
        @if(Session::has('message'))  
        var type = "{{ Session::get('alert-type', 'info') }}";  
        switch (type) {  
            case 'info':  
                toastr.info("{{ Session::get('message') }}");  
                break;  
            case 'success':  
                toastr.success("{{ Session::get('message') }}");  
                break;  
            case 'warning':  
                toastr.warning("{{ Session::get('message') }}");  
                break;  
            case 'error':  
                toastr.error("{{ Session::get('message') }}");  
                break;  
        }  
        @endif  
    </script>  
</body>  

</html>