@extends('layouts.guest')
@section('title', 'Contact Us')

@section('content')
@section('crumb-overlay-text') Contact Us @stop
<div class="card">
<div class="contact-container">
    <div class="contact-header">
    
        <p>We would love to hear from you!</p>
        <p>Email: <a href="mail:info@activepixel">info@activepixel.com</a></p>
    </div>
    
    <form class="contact-form">
        <div class="form-group">
            <input type="text" id="first-name" name="first-name" placeholder="First Name" required>
            <input type="text" id="last-name" name="last-name" placeholder="Last Name" required>
        </div>
        
        <div class="form-group">
            <input type="text" id="contact-number" name="contact-number" placeholder="Contact Number">
            <input type="email" id="email" name="email" placeholder="Email Address" required>
        </div>
        
        <div class="form-group">
            <textarea id="message" name="message" placeholder="Your Message..." required></textarea>
        </div>
        
        <button type="submit">Submit</button>
    </form>
</div>
</div> 
<style>
    .contact-container {
        max-width: 800px;
        margin: 0 auto; /* Center the container horizontally */
        padding: 20px;
        text-align: center; /* Center text and elements inside the container */
    }

    .contact-header h1 {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .contact-header p {
        font-size: 18px;
        margin-bottom: 5px;
    }

    .contact-form {
        margin-top: 20px;
    }

    .form-group {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .form-group input,
    .form-group textarea {
        width: 48%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .form-group textarea {
        width: 100%;
        height: 150px;
        resize: none;
    }

    button[type="submit"] {
        background-color: #5ce1e6;
        color: white;
        padding: 15px 30px;
        font-size: 18px;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
        background-color: #0097b2;
    }

    @media (max-width: 768px) {
        .form-group {
            flex-direction: column;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>
@endsection

