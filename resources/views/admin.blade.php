@include('partials.header')
<body class="antialiased">

<div class="w3-top">
    <div class="w3-bar w3-white w3-card" id="myNavbar">
        <a href="{{ url('/') }}" class="w3-bar-item w3-button w3-wide">LOGO</a>
        <!-- Right-sided navbar links -->
        <div class="w3-right w3-hide-small">
            <a href="#about" class="w3-bar-item w3-button">ABOUT</a>
            <a href="#pricing" class="w3-bar-item w3-button"><i class="fa fa-money"></i> Pricing</a>
            <a href="#book" class="w3-bar-item w3-button"><i class="fa fa-th"></i> Book Now</a>
            <a href="#contact" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i> CONTACT</a>
            @guest
                <a class="w3-bar-item w3-button" href="{{ route('login') }}">{{ __('Login') }}</a>
            @else
                <a class="w3-bar-item w3-button" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endguest
        </div>
        <!-- Hide right-floated links on small screens and replace them with a menu icon -->

        <a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium" onclick="w3_open()">
            <i class="fa fa-bars"></i>
        </a>
    </div>
</div>
<div  class="w3-container" style="padding:50px 16px">
    <div class="w3-row">
        @yield('content')
    </div>
</div>

</body>
@include('partials.footer')
