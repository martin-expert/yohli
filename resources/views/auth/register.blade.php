@extends('layouts.master')
@section('title', 'Register')
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
					<h3 style="font-size: 26px;">Let's create your account!</h3>
					<span>Already have an account? <a href="login">Log In!</a></span>
				</div>

				<!-- Account Type -->
             <!-- Form -->
			<form method="post" id="register-account-form" action="/register">
                @csrf
				@if ($errors->has('account-type'))
                        <span class="text-sm text-red-500">
                            <strong> {{ $errors->first('account-type') }}</strong>
                        </span>
                    @endif
			  <div class="account-type">
                   
					<div>
						<input type="radio" name="account-type" value="freelancer" id="freelancer-radio" class="account-type-radio" {{ old('account-type') == 'freelancer' ? 'checked' : '' }}/>
						<label for="freelancer-radio" class="ripple-effect-dark"><i class="icon-material-outline-account-circle"></i> Freelancer</label>
					</div>
					<div>
						<input type="radio" name="account-type" value="hirer"  id="employer-radio" class="account-type-radio" {{ old('account-type') == 'hirer' ? 'checked' : '' }}/>
						<label for="employer-radio" class="ripple-effect-dark"><i class="icon-material-outline-business-center"></i> Hirer</label>
					</div>
				</div>  
					
                    @if ($errors->has('first_name'))
                    <span class="text-sm text-red-500">
                        <strong> {{ $errors->first('first_name') }}</strong>
                    </span>
                    @endif
					<div class="input-with-icon-left">
						<i class="icon-material-outline-person-pin"></i>
						<input type="text" class="input-text with-border form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" value="{{ old('first_name') }}" name="first_name" id="name" placeholder="First Name" required/>
                    </div>
                    
                    @if ($errors->has('name'))
                    <span class="text-sm text-red-500">
                        <strong> {{ $errors->first('last_name') }}</strong>
                    </span>
                    @endif
					<div class="input-with-icon-left">
						<i class="icon-material-outline-person-pin"></i>
						<input type="text" class="input-text with-border form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" value="{{ old('last_name') }}" name="last_name" id="name" placeholder="Last Name" required/>
					</div>

                    @if ($errors->has('email'))
                    <span class="text-sm text-red-500">
                        <strong> {{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                    <div class="input-with-icon-left">
						<i class="icon-material-baseline-mail-outline"></i>
						<input type="text" class="input-text with-border form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" name="email" id="email" placeholder="Email Address" required/>
					</div>
                    @if ($errors->has('password'))
                    <span class="text-sm text-red-500">
                        <strong> {{ $errors->first('password') }}</strong>
                    </span>
                    @endif
					<div class="input-with-icon-left" title="Should be at least 8 characters long" data-tippy-placement="bottom">
						<i class="icon-material-outline-lock"></i>
						<input type="password" class="input-text with-border form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"  name="password" id="password" placeholder="Password" required/>
					</div>

					<div class="input-with-icon-left">
						<i class="icon-material-outline-lock"></i>
						<input type="password" class="input-text with-border" name="password_confirmation" id="password-confirm" placeholder="Repeat Password" required/>
					</div>

					@if ($errors->has('country'))
                    <span class="text-sm text-red-500">
                        <strong> {{ $errors->first('country') }}</strong>
                    </span>
                    @endif
					<select
					class="select-picker pl-10"
					data-size="7"
					data-live-search="true"
					name="country"
					required
					>
						<option value>All Countries</option>
						@foreach($countries as $country)
						<option value="{{ $country->id }}">{{ $country->name }}</option>
						@endforeach
					</select>
				
				<!-- Button -->
				<button class="button full-width button-sliding-icon ripple-effect margin-top-10" type="submit" form="register-account-form">Register <i class="icon-material-outline-arrow-right-alt"></i></button>
				<!-- Social Login -->
				<!-- <div class="social-login-separator"><span>or</span></div>
				<div class="social-login-buttons">
					<button class="facebook-login ripple-effect"><i class="icon-brand-facebook-f"></i> Register via Facebook</button>
					<button class="google-login ripple-effect"><i class="icon-brand-google-plus-g"></i> Register via Google+</button>
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
