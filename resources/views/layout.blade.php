@include('partials.header')
    <body class="antialiased">

        <div class="w3-top">
            <div class="w3-bar w3-white w3-card" id="myNavbar">

                <!-- Right-sided navbar links -->
                <div class="w3-right w3-hide-small">
                    @guest
                    <a href="#about" class="w3-bar-item w3-button">About</a>
                    <a href="#book" class="w3-bar-item w3-button">Pricing</a>
                    <a href="#book" class="w3-bar-item w3-button">Book Now</a>
                    <a href="#contact" class="w3-bar-item w3-button">Contact</a>
                    @endguest
                    @auth
                        <a class="w3-bar-item w3-button" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endauth
                </div>
                <!-- Hide right-floated links on small screens and replace them with a menu icon -->

                <a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium" onclick="myFunction()">
                    <i id="menuiconmobile" class="fa fa-bars"></i>
                </a>
            </div>
        </div>
        <!-- Navbar on small screens (remove the onclick attribute if you want the navbar to always show on top of the content when clicking on the links) -->
        <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-top">
            <a href="#about" class="w3-bar-item w3-button w3-padding-large w3-right-align" onclick="myFunction()">About</a>
            <a href="#book" class="w3-bar-item w3-button w3-padding-large w3-right-align" onclick="myFunction()">Pricing</a>
            <a href="#book" class="w3-bar-item w3-button w3-padding-large w3-right-align" onclick="myFunction()">Book</a>
            <a href="#contact" class="w3-bar-item w3-button w3-padding-large w3-right-align" onclick="myFunction()">Contact</a>
        </div>

        <header class="w3-container w3-center splash-background">
            <h1 class="w3-margin w3-jumbo title-heading">Impulse Nails</h1>
            <div style="margin: 10px auto 10px auto!important;">
                <a href="https://www.facebook.com/ImpulseNailsCph" class="fa fa-facebook"></a>
                <a href="https://www.instagram.com/impulsenailscph/" class="fa fa-instagram"></a>
            </div>

            <a class="w3-button w3-black w3-padding-large w3-large w3-margin-top call-to-action" href="#book">Book Now!</a>
        </header>

        <!-- About Section -->
        <div class="w3-container" style="padding:50px 16px" id="about">
            <div class="w3-row">
                <div class="w3-col s2 m3 w3-center"><p></p></div>
                <div class="w3-col s8 m6 w3-center">
                    <h2>About</h2>
                        <p>My name is Karin Keight. I am a newly qualified
                            acrylic nail technician through <a href="https://www.youngnails.com/">Young Nails</a>. I recently started my home based salon in Copenhagen Central.
                            I use top quality products. I absolutely love what I do, making nails beautiful is my passion and hope for my clients
                            to share in my passion.
                        </p>
                    </div>
                </div>
                <div class="w3-col s2 m3 w3-center"><p></p></div>
            </div>

        </div>

        <!-- Header -->
        <span class="important-anchor" id="book"></span>
        <div class="w3-container w3-center important-notice" style="padding:5px 16px; background-color:#d6dfe2;">
            <div class="w3-row">
                <div class="w3-col s2 m3 w3-center"><p></p></div>
                <div class="w3-col s8 m6 w3-center">
                    <h2 style="color:#e20c0c">Important</h2>
                    <ul style="text-align: left!important;">
                        <li>Payments, Cash only, unfortunately I do not have MobilePay.</li>
                        <li>In case of a delay, please let me know how long, if the delay exceeds 15 min, the appointment will be cancelled.</li>
                        <li>When booking online, please ensure that you request all services required to ensure the correct amount of time is allocated to your appointment.</li>
                        <li>Prices are subject to change.</li>
                    </ul>
                    <p class="w3-medium" style="color: black">If you have any questions or are unsure of anything, please don’t hesitate to ask</p>
                </div>
                <div class="w3-col s2 m3 w3-center"><p></p></div>
            </div>

        </div>
        <div  class="w3-container " style="padding:0px 16px;" >
            <div class="w3-row">
                <div class="w3-col m3 w3-center"><p></p></div>
                <div class="w3-col m6 w3-center">
                    <h2>Book Now</h2>
                    @yield('content')

                </div>

                <div class="w3-col m3 w3-center"><p></p></div>
            </div>
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
                        Axel Heides Gade,
                        Kobenhavn S,
                        2300</p>
                </div>
                <div class="w3-col s3 w3-center"><p></p></div>
            </div>

        </div>
    <style>
        @media only screen and (max-width: 600px) {
            #book {
                padding:0px 0px 0px!important;
            }
            #navDemo {
                right: 0px;
            }
            .splash-background{
                background-position-x: -300px!important;
            }
        }
        .fa {
            padding: 5px;
            font-size: 15px;
            width: 25px;
            text-align: center;
            text-decoration: none;
            margin: 5px 2px;
        }

        /* Add a hover effect if you want */
        .fa:hover {
            opacity: 0.7;
        }

        /* Set a specific color for each brand */

        /* Facebook */
        .fa-facebook {
            background: #3B5998;
            color: white;
            font-size: 35px;
            width: 45px;
        }

        /* Twitter */
        .fa-instagram {
            background: #125688;
            color: white;
            font-size: 35px;
            width: 45px;
        }
        ul {
            list-style: none;
        }
        ul li::before {
            content: "\2022";
            color: #e20c0c;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }
        ul li {
            margin-top: 15px;
        }
        .splash-background {
            padding:100px 16px;
            background: url("img/splash1.webp");
            background-repeat: no-repeat;
            background-size: auto;
            display: flex;
            flex-direction: column;
            height:100vh;
            width:100vw;
            background-size: cover;
        }
        .title-heading {
            margin: auto auto 10px auto!important;
            font-weight: 600;
            font-size:80px;
            color: #1d2124;
            anti
        }
        .call-to-action{
            margin: 10px auto auto auto!important;
            background-color: #1d2124!important;
        }
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .important-anchor {
            display: block;
            height: 40px;
            margin-top: -40px;
            visibility: hidden;
        }
        .w3-show-block, .w3-show {
            display: flex!important;
            width: 100%!important;
        }
        .w3-hide {
            width: 0;
        }
        #navDemo {
            right: 61px;
            align-content: flex-end;
            justify-content: flex-end;
            margin-top: 5px;
        }
        .w3-bar-item {
            padding: 12px 12px!important;
            width: unset!important;
        }
    </style>
    <script>
        function myFunction() {
            var x = document.getElementById("navDemo");
            var w = document.getElementById("menuiconmobile");
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
                w.classList.remove('fa-bars');
                w.classList.add('fa-times');
            } else {
                x.className = x.className.replace(" w3-show", "");
                w.classList.remove('fa-times');
                w.classList.add('fa-bars');
            }
        }

        // When the user clicks anywhere outside of the modal, close it
        var modal = document.getElementById('ticketModal');
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    </body>
@include('partials.footer')
