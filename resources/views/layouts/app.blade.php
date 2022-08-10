<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <main>
        <div class="container py-4">
            <header>
                <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">

                    <a href="{{ route('home') }}" class="d-flex align-items-center text-dark text-decoration-none">
                        <ion-icon name="qr-code-outline" size="large"></ion-icon>
                        <span class="fs-3">{{ config('app.name', 'Laravel') }}</span>
                    </a>

                    <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">
                        <a class="me-4 py-2 text-dark text-decoration-none" href="{{ route('home') }}">
                            <ion-icon name="document-text-outline"></ion-icon> Send PDF
                        </a>
                        @guest
                        @if (Route::has('login'))
                        <a class="me-4 py-2 text-dark text-decoration-none" href="{{ route('login') }}">
                            <ion-icon name="log-in-outline"></ion-icon> {{ __('Login') }}
                        </a>
                        @endif
                        @if (Route::has('register'))
                        <a class="me-4 py-2 text-dark text-decoration-none" href="{{ route('register') }}">
                            <ion-icon name="person-outline"></ion-icon> {{ __('Register') }}
                        </a>
                        @endif
                        @else
                        <a class="me-4 py-2 text-dark text-decoration-none" href="{{ route('admin') }}">
                            <ion-icon name="grid-outline"></ion-icon> Admin Dashboard
                        </a>
                        <div class="me-4 py-2 text-dark text-decoration-none"><b>{{ Auth::user()->name }}</b></div>
                        <a class="me-4 py-2 text-dark text-decoration-none" href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <ion-icon name="log-out-outline"></ion-icon> {{ __('Logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @endguest
                    </nav>
                </div>
            </header>

            <div class="container">
                @yield('content')
            </div>

            <footer class="pt-3 mt-4 text-muted border-top">
                &copy; 2022
            </footer>
        </div>
    </main>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>

</html>