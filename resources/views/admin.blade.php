@include('partials.header')
<body class="antialiased">

<div class="w3-top">
    <div class="w3-bar w3-white w3-card" id="myNavbar">
        <a href="{{ url('/') }}" class="w3-bar-item w3-button w3-wide">LOGO</a>
        <!-- Right-sided navbar links -->
        <div class="w3-right w3-hide-small">
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

        <a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium" onclick="myFunction()">
            <i class="fa fa-bars"></i>
        </a>
    </div>
</div>
<div id="navDemo" class="w3-bar-block w3-white w3-top">
    <a class="w3-bar-item w3-button text-right" href="{{ route('logout') }}"
       onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
        {{ __('Logout') }}
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
<div  class="w3-container" style="padding:50px 16px">
    <div class="w3-row">
        @yield('content')
    </div>
</div>
<script>
    // Used to toggle the menu on small screens when clicking on the menu button
    function myFunction() {
        var x = document.getElementById("navDemo");
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }
</script>
</body>
@include('partials.footer')
