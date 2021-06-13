@include('partials.header')
    <body class="antialiased">

        <div class="w3-top">
            <div class="w3-bar w3-white w3-card" id="myNavbar">

                <!-- Right-sided navbar links -->
                <div class="w3-right w3-hide-small">
                    <a href="#about" class="w3-bar-item w3-button">About</a>
                    <a href="#book" class="w3-bar-item w3-button">Pricing</a>
                    <a href="#book" class="w3-bar-item w3-button">Book Now</a>
                    <a href="#contact" class="w3-bar-item w3-button">Contact</a>
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
        <!-- Navbar on small screens (remove the onclick attribute if you want the navbar to always show on top of the content when clicking on the links) -->
        <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top:38px">
            <a href="#about" class="w3-bar-item w3-button w3-padding-large w3-right-align" onclick="myFunction()">About</a>
            <a href="#book" class="w3-bar-item w3-button w3-padding-large w3-right-align" onclick="myFunction()">Pricing</a>
            <a href="#book" class="w3-bar-item w3-button w3-padding-large w3-right-align" onclick="myFunction()">Book Now</a>
            <a href="#contact" class="w3-bar-item w3-button w3-padding-large w3-right-align" onclick="myFunction()">Contact</a>
        </div>

        <header class="w3-container w3-center" style="padding:100px 16px;background: linear-gradient( 180deg, rgba(139, 225, 255, 1) 0%, rgba(0, 241, 255, 1) 100% );">
            <h1 class="w3-margin w3-jumbo">Impulse Nails</h1>
            <div>
                <a href="https://www.facebook.com/ImpulseNailsCph" class="fa fa-facebook"></a>
                <a href="https://www.instagram.com/impulsenailscph/" class="fa fa-instagram"></a>
            </div>

            <a class="w3-button w3-black w3-padding-large w3-large w3-margin-top" href="#book">Book Now!</a>
        </header>

        <!-- About Section -->
        <div class="w3-container" style="padding:50px 16px" id="about">
            <div class="w3-row">
                <div class="w3-col s3 w3-center"><p></p></div>
                <div class="w3-col s6 w3-center">
                    <h2>ABOUT</h2>
                        <p>My Name in Karin Keight. I am a newly qualifed
                            nail technician through <a href="https://www.youngnails.com/">Young Nails</a>. I recently started my home based salon in the Copenhagen Central Area.
                            I use top quality products. I absolutely love what I do, making nails beautiful is my passion and hope for my clients
                            to share in my passion.</p>
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
        <div id="book" class="w3-container " style="padding:50px 16px;" >
            <div class="w3-row">
                <div class="w3-col m3 w3-center"><p></p></div>
                <div class="w3-col m6 w3-center">
                    <h2>Book Now!!</h2>
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
                        Axel Hedies Gade,
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
                width: 30%;
                right: 0px;
            }
        }
        .fa {
            padding: 7px;
            font-size: 15px;
            width: 20px;
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
        }

        /* Twitter */
        .fa-instagram {
            background: #125688;
            color: white;
        }
    </style>
    <script>
        function myFunction() {
            var x = document.getElementById("navDemo");
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
            } else {
                x.className = x.className.replace(" w3-show", "");
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
