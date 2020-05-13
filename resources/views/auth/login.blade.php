@extends('layouts.master')
@section('title', 'Log In')
@section('title_bar')
    @include('partials.title_bar')
@endsection

@section('content')
 <div class="container">
	<div class="row">
		<div class="col-xl-5 offset-xl-3">


			<div class="login-register-page">
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>We're glad to see you again!</h3>
					<span>Don't have an account? <a href="register">Sign Up!</a></span>
				</div>
					
				<!-- Form -->
				<form method="POST" id="login-form" action="/login">
                    @csrf
                    @if ($errors->has('email'))
                    <span class="text-sm text-red-500">
                        <strong> {{ $errors->first('email') }}</strong>
                    </span>
                    @endif
					<div class="input-with-icon-left">
						<i class="icon-material-baseline-mail-outline"></i>
						<input type="text" class="input-text with-border" value="{{ old('email') }}" name="email" id="email" placeholder="Email Address" required/>
					</div>
                    @if ($errors->has('password'))
                        <span class="text-sm text-red-500">
                            <strong> {{ $errors->first('password') }}</strong>
                        </span>
                    @endif
					<div class="input-with-icon-left">
						<i class="icon-material-outline-lock"></i>
						<input type="password" class="input-text with-border" name="password" id="password" placeholder="Password" required/>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} id="rememeber">
                        <label for="rememeber"><span class="checkbox-icon"></span> rememeber me</label>
                    </div>

					<a  href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
				
				<!-- Button -->
				<button class="button full-width button-sliding-icon ripple-effect margin-top-10" type="submit" form="login-form">Log In <i class="icon-material-outline-arrow-right-alt"></i></button>
				
				<!-- Social Login -->
				<!-- <div class="social-login-separator"><span>or</span></div>
				<div class="social-login-buttons">
					<button class="facebook-login ripple-effect"><i class="icon-brand-facebook-f"></i> Log In via Facebook</button>
					<button class="google-login ripple-effect"><i class="icon-brand-google-plus-g"></i> Log In via Google+</button>
				</div> -->
			</div>
            </form>


		</div>
	</div>
</div>


<!-- Spacer -->
<div class="margin-top-70"></div>
<!-- Spacer / End-->
@endsection
