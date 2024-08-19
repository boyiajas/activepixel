@extends('layouts.guest')
@section('title') Terms & Conditions @stop
@section('content')

<style>
    .crumb{
        background-image: url('assets/img/runners.jpeg');
        height: 300px;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        animation: fadeInImage 2s ease-in forwards;
      
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
        <h1>Terms & Conditions</h1>
    </div>
</div>
