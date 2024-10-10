<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>
        @yield('title') 
    </title>
    <link rel="shortcut icon" type="image/x-icon" href="{{URL::to('assets/img/favicon.ico')}}">
    <link rel="stylesheet" href="{{URL::to('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{URL::to('assets/plugins/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('assets/plugins/fontawesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{URL::to('assets/css/feathericon.min.css')}}">
    <link rel="stylesheet" href="{{URL::to('assets/plugins/morris/morris.css')}}">
    <link rel="stylesheet" href="{{URL::to('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{ URL::to('assets/css/toastr.min.css') }}">
    <script src="{{ URL::to('assets/js/toastr_jquery.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/toastr.min.js') }}"></script>
    <link rel="stylesheet" href="{{URL::to('assets/css/front-end.css')}}">


    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

    {{-- message toastr --}}

    <style>
        .event-card {
            padding: 0px;
        }

        .event-card .card-img-top {
            height: 200px;
        }

        .event-card .card-body {
            padding: 10px;
        }

        .event-gallery {
            min-height: 300px;
        }

        body {
            background-color: #fff;
            height: fit-content;
        }

        .navbar-brand {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            z-index: 999;
        }

        .navbar-brand img {
            height: 50px;
        }
        .navbar-brand.footer{
            left: none !important;
            position:initial;
            transform: initial;
        }

        .navbar-brand.footer img{
            height: 40px;
        }
        
        .navbar-nav {
            flex-direction: row;
        }

        .nav-item {
            margin-right: 15px;
            position: relative;
            display: inline-block;
        }

        .nav-item:last-child {
            margin-right: 0;
        }

        .navbar-nav.ml-auto {
            margin-left: auto;
        }

        .navbar {
            background-color: #0097b2;
            padding: 0px;
            /* Change to your desired color */
        }

        .navbar-nav .nav-link {
            color: #ffffff;
            /* Change to the color you want for text */
            font-weight: bold;
            padding: 20px 20px;
            /* Adjust padding as needed */
            display: inline-block;
            position: relative;
        }

        .navbar-nav .nav-link:hover {
            color: #f0f0f0;
            /* Text color on hover */
            background-color: #5ce1e6;
            /* Background color on hover */
            /* text-decoration: underline; */
            /* Underline on hover */
            border-radius: 2px;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar-nav .nav-link::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #5ce1e6;
            z-index: -1;
            transform: scaleY(0);
            transform-origin: top;
            transition: transform 0.3s ease-in-out;
            border-radius: 4px;
        }

        .navbar-nav .nav-link:hover::before {
            transform: scaleY(1);
        }

        .navbar-nav .nav-link img {
            height: 24px;
            /* Set the height of the icon */
            background-color: transparent;
            /* Ensure the background is transparent */
            border: none;
            /* Remove any border */
            padding: 0;
            /* Remove any padding */

        }

        .navbar-nav.transparent-image {
            display: block;
            /* Ensure the image is treated as a block element */
            width: 300px;
            /* Adjust width as needed */
            height: auto;
            /* Maintain aspect ratio */
            background-color: transparent;
            /* Ensure no background color is applied */
        }

        .cart {
            background-color: #009ce7;
            background-color: #fff;
            color: #009ce7;
            border-radius: 15px;
            padding: 3px !important;
            padding-left: 10px !important;
            padding-right: 10px !important;
            box-shadow: 2px 2px 2px 2px #0086c6;
        }

        .cart i,
        span {
            color: #009ce7;
        }

        .cart span {
            font-size: 20px;
        }

        .navbar-toggler {
            display: none;
        }

        .nav-link-cart {
            padding: 14px 20px !important;
        }

        /* Mobile navbar styles */
        @media (max-width: 767px) {
            .navbar-nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-item {
                margin-bottom: 10px;
            }

            .navbar-brand {
                position: static;
                transform: none;
                margin-top: 0;
                margin-bottom: 20px;
            }

            .navbar-collapse {
                display: none;
            }

            .navbar-toggler {
                display: block;
                background-color: #5ce1e6;
                border: none;
                border-radius: 4px;
                padding: 10px;
                color: #ffffff;
            }

            .navbar-toggler:focus {
                outline: none;
            }

            .navbar-nav {
                display: none;
                flex-direction: column;
                width: 100%;
                background-color: #0097b2;
                padding: 10px;
            }

            .navbar-nav.active {
                display: flex;
            }

            .nav-item {
                width: 100%;
                text-align: left;
            }
        }
    </style>
</head>

<body>
    {{-- message --}}
    {!! Toastr::message() !!}
    <nav class="navbar">
        <div class="container">
            <!-- Navbar Toggler for Mobile -->
            <button class="navbar-toggler" onclick="toggleNavbar()">☰</button>
            <!-- Left-aligned links -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/events/all">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about-us">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact-us">Contact Us</a>
                </li>
            </ul>

            <!-- Centered Logo -->
            <a class="navbar-brand" href="/">
                <img src="/assets/img/AP - White.png" alt="Site Logo">
            </a>

            <!-- Right-aligned links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    @if(Auth::check())
                        <a class="nav-link" href="/logout">Sign Out</a>
                        <a class="nav-link" href="/customer/dashboard">Dashboard</a>
                    @else
                        <a class="nav-link" href="#how-to-order" style="color:yellow;">How to Order?</a>
                        <a class="nav-link" href="/login">Login/Signup</a>
                    @endif
                </li>
                <li class="nav-item">

                    <a class="nav-link nav-link-cart" href="/cart">
                        <div class="cart">
                            <i class="fas fa-shopping-cart"></i>
                            <span>
                                @php
                                    $cartItems = [];
                                    $totalPrice = 0;

                                    if (Auth::check()) {
                                        // Get cart items for logged-in user
                                        $cartItems = App\Models\Cart::where('user_id', Auth::id())->get();
                                    } else {
                                        // Get cart items for guest user
                                        $guestToken = session('guest_token');
                                        if ($guestToken) {
                                            $cartItems = App\Models\Cart::where('guest_token', $guestToken)->get();
                                        }
                                    }
                                    $qty = 0;
                                    foreach ($cartItems as $item) {
                                        $qty += $item->quantity;
                                    }
                                    echo $qty;
                                @endphp
                            </span>
                        </div>


                        <!-- <img src="assets/img/cart.jpg" alt="Transparent Image" class="transparent-image" style="height: 24px;"> -->
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <style>
        .crumb {
            background-image: linear-gradient(rgba(0, 0, 0, 0.58), rgba(0, 0, 0, 0.31)), url('/assets/img/runners.jpeg');

            height: 400px;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            animation: fadeInImage 2s ease-in forwards;
            z-index: 1;
            filter: grayscale(1%);
            background-size: auto;
        }

        @keyframes fadeInImage {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .overlay-text {
            
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
            z-index: 2;
            padding: 10px 20px;
            display: initial;
            /* Optional: Adds some padding around the text */
            /* Optional: Rounds the corners of the background */
        }

        .overlay-text h1 {
            font-size: 70px;
            /* Adjust the font size as needed */
            margin: 0;
            /* Removes default margin */
            font-family: 'Arial', sans-serif;
            /* Optional: Customize the font */
            color: white;
            font-weight: bold;
        }

        .about-icon-box {
            width: 300px;
        }

        .about-icon-box img {
            box-shadow: 2px 2px 2px 2px #aaa;
            border-radius: 15px;
            width: 200px;
            height: 150px;
        }

        .banner {
            background-color: #0097b2;
            padding: 20px;
            margin: 20px auto;
            max-width: auto;
            /* Adjust width as needed */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Optional: Add shadow for better card effect */
            z-index: 1;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }



        h2 {
            text-align: center;
            color: #5ce1e6;
            font-size: 32px;
        }

        .step-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .step-box {
            display: flex;
            background: linear-gradient(135deg, #fbfbfb 0%, #bafdff 52%, #fff 100%, #5ce1e6 100%); /* linear-gradient(135deg, #0097b2 0%, #5ce1e6 52%, #0097b2 100%, #5ce1e6 100%); */
            padding: 20px;
            border: 2px solid #ecf0f1;
            border-radius: 5px;
            align-items: center;
            box-shadow: 0 2px 1px rgba(0, 0, 0, 0.1);
        }

        .step-number {
            font-size: 48px;
            font-weight: bold;
            color: #0097b2;
            margin-right: 20px;
            text-align: center;
            line-height: 1;
        }

        .step-content h3 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }

        .step-content p {
            margin: 5px 0 0;
            font-size: 16px;
            color: #34495e;
        }

        @media (min-width: 768px) {
            .step-section {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 30px;
            }

            .step-box {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }

            .step-number {
                margin-right: 0;
                margin-bottom: 15px;
            }
        }
    </style>


    @yield('content')

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <!-- the navbar content ends here -->


    <!-- the footer content goes in here -->

    <footer class="mt-5">
        <div class="footer-container">
        <div class="footer-section">
                <h3>More Info</h3>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/about-us">About Us</a></li>
                    <li><a href="/contact-us">Contact Us</a></li>
                    <li><a href="#how-to-order">How to Order?</a></li>
                </ul>
            </div>
            <div class="footer-divider"></div> <!-- Divider -->
            <div class="footer-section">
                <div>
                    <a class="navbar-brand footer" href="/">
                        <img src="/assets/img/AP - White.png" alt="Site Logo">
                    </a>
                </div>
                
                <div>
                    <br/><h3>Follow Us</h3>
                </div>
                <div class="social-icons">
                    <a href="https://www.instagram.com/activepixel_official" target="_blank"><i
                            class="fab fa-instagram"></i></a>
                    <a href="https://www.facebook.com/people/Active-Pixel/61558232257425" target="_blank"><i
                            class="fab fa-facebook"></i></a>
                </div>
            </div>
            <div class="footer-divider"></div> <!-- Divider -->
            <div class="footer-section">
                <h3>Legals & Help</h3>
                <ul>
                    <li><a href="/terms-and-conditions">Terms & Conditions</a></li>
                    <li><a href="/privacy-policy">Privacy Policy</a></li>
                    <li><a href="/refund-policy">Refund Policy</a></li>
                    <li><a href="{{asset('/uploads/Active Pixel - POPIA 2024.pdf')}}" target="_blank">POPIA</a></li>
                    <li><a href="/contact-us">Need Help?</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Copyright © 2024 | All Rights Reserved | Powered by LeoSath.jpeg</p>
        </div>
    </footer>

    <!-- the footer content ends here -->
    <script>
        $(document).ready(function () {
            /* $(document).bind("contextmenu", function (e) {
                  return false;
              });*/
        });

        function toggleNavbar() {
            var navbar = document.querySelector('.navbar-nav');
            navbar.classList.toggle('active');
        }
    </script>

    <script src="{{URL::to('assets/js/jquery-3.5.1.min.js')}}"></script>
    <script src="{{URL::to('assets/js/popper.min.js')}}"></script>
    <script src="{{URL::to('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::to('assets/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{URL::to('assets/js/script.js')}}"></script>
</body>

</html>