@include('partials.header')
    <body class="antialiased">

        <div class="w3-top">
            <div class="w3-bar w3-white w3-card" id="myNavbar">

                <!-- Right-sided navbar links -->
                <div class="w3-right w3-hide-small">
                    <a href="#about" class="w3-bar-item w3-button">ABOUT</a>
                    <a href="#pricing" class="w3-bar-item w3-button"><i class="fa fa-user"></i> Pricing</a>
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

        <header class="w3-container w3-center" style="padding:100px 16px;background: linear-gradient( 180deg, rgba(139, 225, 255, 1) 0%, rgba(0, 241, 255, 1) 100% );">
            <h1 class="w3-margin w3-jumbo">Impulse Nails</h1>
            <p class="w3-xlarge">By Karin</p>
            <a class="w3-button w3-black w3-padding-large w3-large w3-margin-top" href="#book">Book Now!</a>
        </header>

        <!-- About Section -->
        <div class="w3-container" style="padding:50px 16px" id="about">
            <div class="w3-row">
                <div class="w3-col s3 w3-center"><p></p></div>
                <div class="w3-col s6 w3-center">
                    <h2>ABOUT</h2>
                        <p>My Name in Karin Keight. I am a newly qualifed
                            nail technician. I started my home based salon and
                            work by myself. I absolutely love what I do, doing nails
                            is my passion. I use top quality products and love my clients
                            to share my passion.</p>
                        </p>

                    </div>
                </div>
                <div class="w3-col s3 w3-center"><p></p></div>
            </div>

        </div>

        <!-- Header -->
        <div class="w3-container w3-center" style="padding:5px 16px; background-color:#d6dfe2;">
            <div class="w3-row">
                <div class="w3-col s3 w3-center"><p></p></div>
                <div class="w3-col s6 w3-center">
                    <h1 class="w3-margin w3-large" style="color:red">Important Notice</h1>
                    <p class="w3-medium" style="color: black">Please wear a mask to your appointment. If you are unable to purchase one, please
                        let me know before your appointment and i can supply this for you on your arrival.</p>
                </div>
                <div class="w3-col s3 w3-center"><p></p></div>
            </div>

        </div>
        <div id="book" class="w3-container" style="padding:50px 16px" >
            <div class="w3-row">
                <div class="w3-col s3 w3-center"><p></p></div>
                <div class="w3-col s6 w3-center">
                    <h2>Book Now!!</h2>
                    @yield('content')

                </div>
            </div>
            <div class="w3-col s3 w3-center"><p></p></div>
        </div>

        <!-- Header -->
        <div id="contact" class="w3-container w3-center" style="padding:5px 16px; background: linear-gradient( 180deg, rgba(139, 225, 255, 1) 0%, rgba(0, 241, 255, 1) 100% );">
            <div class="w3-row">
                <div class="w3-col s3 w3-center"><p></p></div>
                <div class="w3-col s6 w3-center">

                    <p class="w3-medium" style="color: black">
                        Mobile +45 52 81 37 11
                        Email:  impulsenailscph@gmail.com</p>
                    <p class="w3-medium" style="color: black">
                        Location
                        Axel Hedies Gade,
                        Kobenhavn S,
                        2300</p>
                </div>
                <div class="w3-col s3 w3-center"><p></p></div>
            </div>

        </div>
    </body>
@include('partials.footer')
