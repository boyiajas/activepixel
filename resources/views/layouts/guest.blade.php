<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>
        @yield('title') 
    </title>
    <link rel="shortcut icon" type="image/x-icon" href="{{URL::to('assets/img/favicon.png')}}">
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
        .navbar-brand {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            z-index: 999;
            margin-top: 80px;

        }

        .navbar-nav {
            flex-direction: row;
        }

        .nav-item {
            margin-right: 15px;
        }

        .nav-item:last-child {
            margin-right: 0;
        }

        .navbar-nav.ml-auto {
            margin-left: auto;
        }

        .navbar {
            background-color: #0097b2;
            /* Change to your desired color */
        }

        .navbar-nav .nav-link {
            color: #ffffff;
            /* Change to the color you want for text */
            font-weight: bold;
        }

        .navbar-nav .nav-link:hover {
            color: #f0f0f0;
            /* Text color on hover */
            background-color: #5ce1e6;
            /* Background color on hover */
            text-decoration: underline;
            /* Underline on hover */
            border-radius: 2px;
            transition: background-color 0.3s, color 0.3s;
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
    </style>
</head>

<body>
    {{-- message --}}
    {!! Toastr::message() !!}
    <nav class="navbar">
        <div class="container">
            <!-- Left-aligned links -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#events">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about-us">About Us</a>
                </li>
            </ul>

            <!-- Centered Logo -->
            <a class="navbar-brand" href="#">
                <img src="assets/img/ActivePixel.PNG" alt="Site Logo" style="height: 150px;">
            </a>

            <!-- Right-aligned links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/login">Login/Signup</a>
                </li>
                <li class="nav-item">

                    <a class="nav-link cart" href="/cart">

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
                        <!-- <img src="assets/img/cart.jpg" alt="Transparent Image" class="transparent-image" style="height: 24px;"> -->
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <style>
        .crumb-container {
            height: 200px;
            position: relative;
        }

        .crumb {
            background-image: url('assets/img/runners.jpeg');
            filter: brightness(0.5);
            height: 200px;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            animation: fadeInImage 2s ease-in forwards;
            z-index: 1;

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
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
            z-index: 2;
            padding: 10px 20px;
        }

        .overlay-text h1 {
            font-size: 60px;
            /* Adjust the font size as needed */
            margin: 0;
            /* Removes default margin */
            font-family: 'Arial', sans-serif;
            /* Optional: Customize the font */
            color: white;
            font-weight: bold;
            margin-top: 55px;
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
    </style>

    <div class="crumb-container">
        <div class="crumb"></div>
        <div class="overlay-text">
            <h1>@yield('crumb-overlay-text') </h1>
        </div>
    </div>


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
                <h3>MORE INFO</h3>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/about-us">About</a></li>
                    <li><a href="/contact-us">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-divider"></div> <!-- Divider -->
            <div class="footer-section">
                <h3>LEGALS & HELP</h3>
                <ul>
                    <li><a href="/terms-and-conditions">Terms & Conditions</a></li>
                    <li><a href="/privacy-policy">Privacy Policy</a></li>
                    <li><a href="#">POPIA</a></li>
                    <li><a href="/contact-us">Need Help?</a></li>
                </ul>
            </div>
            <div class="footer-divider"></div> <!-- Divider -->
            <div class="footer-section">
                <h3>FOLLOW US</h3>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Copyright © 2024 | All Rights Reserved | Powered by LeoSath.jpeg</p>
        </div>
    </footer>

    <!-- the footer content ends here -->


    <script src="{{URL::to('assets/js/jquery-3.5.1.min.js')}}"></script>
    <script src="{{URL::to('assets/js/popper.min.js')}}"></script>
    <script src="{{URL::to('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::to('assets/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{URL::to('assets/js/script.js')}}"></script>
</body>

</html>