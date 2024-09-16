@extends('layouts.guest')
@section('content')
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <div class="loginbox">
                    <div class="login-left"> <img class="img-fluid" src="assets/img/watermark.png" alt="Logo" style=" border-radius: 5px;"> </div>
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1 class="mb-3">Register</h1>
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Enter Name" value="{{ old('name') }}">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Enter Email" value="{{ old('email') }}">
                                </div>
                                <div class="form-group" hidden>
                                    <input type="text" class="form-control @error('role_name') is-invalid @enderror" name="role_name" value="user">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter Password">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                                </div>
                                <div class="form-group">
                                    <div class="captcha">
                                        <span>{!! Captcha::img() !!}</span>
                                        <button type="button" class="btn btn-danger reload" id="reload">&#x21bb</button>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-2">
                                    <input type="text" id="i-captcha" class="form-control{{ $errors->has('captcha') ? ' is-invalid' : '' }}" placeholder="Enter Captcha" name="captcha" required="true" >
                                    @if ($errors->has('captcha'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('captcha') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group mb-0">
                                    <button class="btn btn-primary btn-block" type="submit">Register</button>
                                </div>
                            </form>
                            <div class="login-or">
                                <span class="or-line"></span> 
                                <span class="span-or">or</span> 
                            </div>
                            <div class="social-login"> 
                                <span>Register with</span> 
                                <a href="#" class="facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="google">
                                    <i class="fab fa-google"></i></a> 
                                </div>
                            <div class="text-center dont-have">Already have an account? 
                                <a href="{{ route('login') }}">Login</a> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#reload').click(function(){
            $.ajax({
                type:'GET',
                url: 'reload-captcha',
                success:function(data){
                    $(".captcha span").html(data.captcha)
                }
            });
        });
    </script>
@endsection