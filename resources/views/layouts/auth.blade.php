<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel App</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @vite('resources/css/app.css')
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}

</head>

<body>

    <!-- Display Flash Messages -->
    @if (session('error'))
        <script>
            alert(@json(session('error')));
            // console.log(@json(session('error')));
        </script>
    @endif
    @if (session('success'))
        <script>
            alert(@json(session('success')));
            // console.log(@json(session('success')));
        </script>
    @endif
    <header>
        <nav>
            <a href="{{ route('users.index') }}">Trang chủ</a> |
            <a href="{{ route('login') }}">Đăng nhập</a> |
            <a href="{{ route('register') }}">Đăng kí</a> |

        </nav>
    </header>

    <div class="">
        @yield('content') <!-- This is where child content will be inserted -->
    </div>

    <footer>
        <p>Laravel Training @2025</p>
    </footer>

</body>

</html>
