<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div id="app">
        <nav class="navbar sticky-top navbar-expand-md navbar-dark bg-dark shadow-sm ">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo-new.png') }}" alt="Logo" width="40" height="40">
                    &nbsp;
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @guest
                        @else
                        <li class="nav-item ">
                            <a class="nav-link {{ request()->is('profile*') ? 'active' : '' }}"" href="
                                {{ route('profile') }}">{{ __('Profile') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('all-books*') ? 'active' : '' }}"
                                href="{{ route('all-books') }}">{{ __('Books') }}</a>
                        </li>
                        <li class="nav-item">
                            @php
                            $cart = Session::get('cart', []);
                            $cartLength = count($cart);
                            @endphp
                            <a class="nav-link position-relative {{ request()->is('cart*') ? 'active' : '' }}"
                                href="{{ route('cart') }}">
                                {{ __('Cart') }}
                                @if($cartLength>0)
                                <span id="cartBadge"
                                    class="position-absolute top-2 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{$cartLength}}
                                </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('my-orders*') ? 'active' : '' }}"
                                href="{{ route('my-orders') }}">{{ __('My Orders') }}</a>
                        </li>
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script>
    function fetchCartCount() {
        fetch("{{ route('cart.count') }}")
            .then(response => response.json())
            .then(data => {
                const cartBadge = document.getElementById('cartBadge');
                if(cartBadge){
                    cartBadge.textContent = data.count > 0 ? data.count : '';
                }
            })
            .catch(error => console.error('Error fetching cart count:', error));
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchCartCount();
    });

    setInterval(fetchCartCount, 5000);
    </script>
</body>

</html>