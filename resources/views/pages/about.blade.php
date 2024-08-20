@extends('layouts.guest')
@section('title') About us @stop
@section('content')

<style>
    .crumb {
        background-image: url('assets/img/runners.jpeg');
        filter: brightness(0.5);
        height: 300px;
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
        top: 40%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        text-align: center;
        z-index: 2;
        padding: 10px 20px;
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
</style>
<div class="crumb">
    <div class="overlay-text">
        <h1>About Us</h1>
    </div>
</div>

<div class="card">




    <div class="container">
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="image-content">
                    <img src="assets/img/ActivePixelAboutUs.PNG" alt="Active Pixel" class="rounded-image">
                </div>
            </div>
            <div class="col-md-9">
                <h4>Who Is Active Pixel?</h4>
                <p>Active Pixel is a group of photographers that are set to bring your images of athletics events that
                    are not usually covered every week. We strive to give you an easy way to reach your athletic
                    memories!</p>
                <p>With the gap of not having images accessible and affordable, we have decided to put in the greasy
                    work to give you the best chance of obtaining a memory that lasts a lifetime.</p>

            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-5">

                <h4> Why Choose Active Pixel?</h4>
                <p> We are unbelievably affordable</p>
                <p> We make ourselves available to events!</p>
                <p> We create eye-catching memories for you!</p>
                <p>We are trained professionals with loads of experience!</p>

            </div>
            <div class="col-md-7">
                <div class="icon-section">
                    <div class="row col-md-9">
                        <div class="about-icon-box col-6 px-10">
                            <img src="assets/img/Accessible.PNG" alt="Accessible Icon">
                        </div>
                        <div class="about-icon-box col-6 px-10">
                            <img src="assets/img/Professional.PNG" alt="Professional Icon">
                        </div>
                    </div>
                    <div class="row col-md-9">
                        <div class="about-icon-box col-6 px-10">
                            <img src="assets/img/EyeCatching.PNG" alt="Eye Icon">
                        </div>
                        <div class="about-icon-box col-6 px-10">
                            <img src="assets/img/Affordable.PNG" alt="Affordable Icon">
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="row">
            <div class="col-md-7">
                <h4> Why Go Digital With Active Pixel?</h4>
                <p> Memories that last a life-time!</p>
                <p>A digital image will give you the chance to look it up at anytime and anywhere!</p>
            </div>
            <div class="col-md-5">
                <div class="image-content">
                    <img src="assets/img/digital.PNG" alt="digital" class="rounded-image">
                </div>

            </div>
        </div>
    </div>
</div>

@endsection