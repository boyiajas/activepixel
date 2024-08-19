@extends('layouts.guest')
@section('title') About us @stop
@section('content')

<style>
    .crumb{
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
        padding: 10px 20px; /* Optional: Adds some padding around the text */
        /* Optional: Rounds the corners of the background */
    }

    .overlay-text h1 {
        font-size: 70px; /* Adjust the font size as needed */
        margin: 0; /* Removes default margin */
        font-family: 'Arial', sans-serif; /* Optional: Customize the font */
        color: white;
        font-weight: bold ;
    }
    
</style>
<div class="crumb">
<div class="overlay-text">
        <h1>About Us</h1>
    </div>
</div>

<div class="card">




<div class="container">
    <div class="row ">
        <div class="col-md-3">
            <div class="image-content">
                <img src="assets/img/ActivePixelAboutUs.PNG" alt="Active Pixel" class="rounded-image">
            </div>
        </div>
        <div class="col-md-9">
            <h1>Who Is Active Pixel?</h1>
            <p>Active Pixel is a group of photographers that are set to bring your images of athletics events that are not usually covered every week. We strive to give you an easy way to reach your athletic memories!</p>
            <p>With the gap of not having images accessible and affordable, we have decided to put in the greasy work to give you the best chance of obtaining a memory that lasts a lifetime.</p>
 
        </div>
    </div>
    <div class="row">
    <div class=col-md-6 order-md-last>
        <div class="icon-section">
            <div class="icon-box">
                <img src="assets/img/Accessible.PNG" alt="Accessible Icon">
                <h3>Accessible</h3>
                
            </div>
            <div class="icon-box">
                <img src="assets/img/Professional.PNG" alt="Professional Icon">
                <h3>Professional</h3>
              
            </div>
            <div class="icon-box">
                <img src="assets/img/EyeCatching.PNG" alt="Eye Icon">
                <h3>Eye Catching</h3>
                
            </div>
            <div class="icon-box">
                <img src="assets/img/Affordable.PNG" alt="Affordable Icon">
                <h3>Affordable</h3>
                
            </div>
            </div>
            </div>
          
            <div class="col-md-6 order-md-first">
           
            <h1> Why Choose Active Pixel?</h1>
         <p> We are unbelievably affordable
            We make ourselves available to events!
             We create eye-catching memories for you!
           We are trained professionals with loads of experience!
           </p>
</p>

</div>
</div>
<div class="row">
    <div class="col-md-3">
<div class="image-content">
<img src="assets/img/digital.PNG" alt="digital" class="rounded-image">
    </div>

    </div>

<div class="col-md-9">
<h1> Why Go Digital With Active Pixel?</h1>
<p> Memories that last a life-time!
    A digital image will give you the chance to look it up at anytime and anywhere!
</p>
</div>
</div>
</div>     
        </div>
    
    @endsection