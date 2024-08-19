@extends('layouts.guest')
@section('title') Contact us @stop
@section('content')

<h3>contact us</h3>
<div class="form-container">
        <h2>Send Us A Message</h2>
        <p class="subheading">All fields marked with * are required</p>
        <form>
            <div class="form-group">
                <label for="first-name">Name <span>*</span></label>
                <div class="name-fields">
                    <input type="text" id="first-name" name="first-name" placeholder="First" required>
                    <input type="text" id="last-name" name="last-name" placeholder="Last" required>
                </div>
            </div>

            <div class="form-group">
                <label for="contact-number">Contact Number</label>
                <input type="text" id="contact-number" name="contact-number" placeholder="Contact Number">
            </div>

            <div class="form-group">
                <label for="email">Email <span>*</span></label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>

            <div class="form-group">
                <label for="message">Comment or Message <span>*</span></label>
                <textarea id="message" name="message" placeholder="Comment or Message" required></textarea>
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>

</body>

@endsection<div class="info-section">
            <div class="info-item">
                <img src="location-icon.png" alt="Address Icon">
                <div>
                    <h3>ADDRESS</h3>
                    <p>14 Bamboo Lane, New Germany, Kwa-Zulu Natal, South Africa</p>
                </div>
            </div>
            <div class="info-item">
                <img src="phone-icon.png" alt="Phone Icon">
                <div>
                    <h3>PHONE</h3>
                    <p>(+27) 73 341 8052</p>
                </div>
            </div>
            <div class="info-item">
                <img src="email-icon.png" alt="Email Icon">
                <div>
                    <h3>Email</h3>
                    <p>info@activepixel.co.za</p>
                </div>
            </div>
            <div class="info-item">
                <img src="website-icon.png" alt="Website Icon">
                <div>
                    <h3>WEBSITE</h3>
                    <p>www.activepixel.co.za</p>
                </div>
            </div>
            <div class="social-media">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <img src="facebook-icon.png" alt="Facebook Icon">
                    <img src="instagram-icon.png" alt="Instagram Icon">
                </div>
            </div>
        </div>
    </div>