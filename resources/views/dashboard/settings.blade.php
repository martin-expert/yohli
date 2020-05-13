@extends('layouts.dashboard_master')
@section('title', 'Settings')

@section('content')
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">

            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-account-circle"></i> Basic Profile</h3>
            </div>

            <div class="content with-padding padding-bottom-0">

                <form method="post" action="/update_basic_info" enctype="multipart/form-data">
                    @csrf

                <div class="row">
                    <div class="col-auto">
                        <div class="avatar-wrapper" data-tippy-placement="bottom" title="Change Avatar">
                           @if (sizeof($user->getMedia('profile')) == 0)
                            <img class="profile-pic" src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt="" />
                           @else
                           <img class="profile-pic" src="{{ $user->getFirstMediaUrl('profile') }}" alt="" /> 
                           @endif
                            <div class="upload-button"></div>
                            <input class="file-upload" type="file" accept="image/*" name="picture"/>
                        </div>
                    </div>

                        <div class="flex flex-row flex-wrap max-w-5xl">

                            <div class="col-xl-6">
                                <div class="submit-field">
                                    <h5>First Name</h5>
                                    <div class="input-with-icon-left">
                                        <i class="icon-material-outline-account-circle"></i>
                                        <input type="text" class="with-border" value="{{ old('first_name', $user->user->first_name) }}" name="first_name">
                                    </div>
                                </div>
                            </div> 

                            <div class="col-xl-6">
                                <div class="submit-field">
                                    <h5>Last Name</h5>
                                    <div class="input-with-icon-left">
                                        <i class="icon-material-outline-account-circle"></i>
                                        <input type="text" class="with-border" value="{{ old('last_name', $user->user->last_name) }}" name="last_name" required>
                                    </div>
                                </div>
                            </div>                            

                            <div class="col-xl-6">
                                <div class="submit-field">
                                    <h5>Email</h5>
                                    <div class="input-with-icon-left">
                                        <i class="icon-material-baseline-mail-outline"></i>
                                        <input type="text" class="with-border" value="{{ old('email', $user->email) }}" name="email" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="submit-field">
                                    <h5>Country</h5>
                                    <select class="selectpicker with-border" data-size="7" title="Select Country" data-live-search="true" name="country_id" id="country" required>
                                        @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" {{ $user->country_id == $country->id ? 'selected="selected"' : '' }}>
                                            {{ $country->name }}
                                        </option> 
                                        @endforeach                                 
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <!-- Account Type -->
                                <div class="submit-field">
                                    <h5>Phone</h5>
                                    <div class="input-with-icon-left">
                                        <i class="intl_num">+0</i>
                                        <input type="text" class="with-border" value="{{ old('phone', $user->phone) }}" name="phone" required>
                                    </div>                                    
                                </div>
                            </div>

                            {{-- <div class="col-xl-6">
							 	<!-- Account Type -->
							 	<div class="submit-field">
							 		<h5>Account Type</h5>
							 		<div class="account-type">
							 			<div>
							 				<input type="radio" name="account_type" value="freelancer" id="freelancer-radio" class="account-type-radio" {{ $user->type == 'freelancer' ? 'checked="checked"' : '' }}/>
							 				<label for="freelancer-radio" class="ripple-effect-dark"><i class="icon-material-outline-account-circle"></i> Freelancer</label>
							 			</div>
							 			<div>
							 				<input type="radio" name="account_type" value="hirer" id="employer-radio" class="account-type-radio"  {{ $user->type == 'hirer' ? 'checked="checked"' : '' }}/>
							 				<label for="employer-radio" class="ripple-effect-dark"><i class="icon-material-outline-business-center"></i> Hirer</label>
							 			</div>
							 		</div>
							 	</div>
                             </div> --}}
                             
                             <div class="col-xl-12">
                                <hr>
                                <br>
                             </div>


                            <!-- Button -->
                            <div class="col-xl-12">
                                <div class="submit-field">
                                    <button type="submit" class="button ripple-effect">Update Profile</button>
                                </div>
                            </div>      

                        </div>
                </div>

                </form>

            </div>
        </div>
    </div>

    @role('freelancer')
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box">

            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-face"></i> Freelancer Profile</h3>
            </div>
            

            <div class="content">
                <form method="post" action="/update_freelancer_info" enctype="multipart/form-data">
                    @csrf
                    <ul class="fields-ul">
                    <li>
                        <div class="row max-w-5xl">
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <div class="bidding-widget">
                                        <!-- Headline -->
                                        <span class="bidding-detail">Set your <strong>minimal hourly rate</strong></span>

                                        <!-- Slider -->
                                        <div class="bidding-value margin-bottom-10">$<span id="biddingVal"></span></div>
                                        <input class="bidding-slider" type="text" name="rate" value="{{ old('rate', $user->rate) }}" data-slider-handle="custom" data-slider-currency="$" data-slider-min="5" data-slider-max="150" data-slider-value="{{ $user->rate ?? 20 }}" data-slider-step="1" data-slider-tooltip="hide" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Skills <i class="help-icon" data-tippy-placement="right" title="Add up to 10 skills"></i></h5>

                                    {{-- <select class="selectpicker" data-size="7" data-live-search="true" name="skills[]" multiple>
                                        @foreach ($skills as $skill)
                                            <option value="{{ $skill->id }}"
                                                    {{ isset($user->skills) && in_array($skill->id, $user->skills()->pluck('id')->toArray()) ? 'selected' : '' }}
                                                >{{ $skill->title }}
                                            </option>
                                        @endforeach
                                    </select> --}}

                                    <select class="skills-dropdown" multiple="multiple" name="skills[]">
                                        @foreach ($skills as $skill)
                                            <option value="{{ $skill->id }}" {{ isset($user->skills) && in_array($skill->id, $user->skills()->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $skill->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Attachments</h5>
                                    
                                    {{-- <!-- Attachments -->
                                    <div class="attachments-container margin-top-0 margin-bottom-0">
                                        @foreach ($user->getMedia('cv') as $file)
                                            <div class="attachment-box ripple-effect">
                                                <span class="text-capitalize">{{ $file->name }} </span>
                                                <i class="text-uppercase">{{ $file->extension }}</i>
                                                <button type="button" class="remove-attachment" data-tippy-placement="top" title="Remove"></button>
                                            </div> 
                                        @endforeach
                                    </div>
                                    <div class="clearfix"></div> --}}
                                    
                                    <!-- Upload Button -->
                                    <div class="uploadButton margin-top-0">
                                        <input class="uploadButton-input" type="file" accept="image/*, application/pdf" id="upload" name="documents[]" multiple/>
                                        <label class="uploadButton-button ripple-effect" for="upload">Upload Files</label>
                                        <span class="uploadButton-file-name">Maximum file size: 2 MB</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row max-w-5xl">
                            <div class="col-xl-6">
                                <div class="submit-field">
                                    <h5>Professional Headline</h5>
                                    <input type="text" class="with-border" value="{{ old('headline', $user->headline) }}" name="headline" >
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="submit-field">
                                    <h5>Introduce Yourself</h5>
                                    <textarea cols="30" rows="5" class="with-border" name="description">{{  old('description', $user->description) }}</textarea>
                                </div>
                            </div>

                        </div>
                    </li>

                    <li>
                        <div class="row max-w-5xl">
                            <div class="col-xl-12">
                                <div class="submit-field">
                                    <button type="submit" class="button ripple-effect">Submit Changes</button>
                                </div>
                            </div> 

                        </div>
                    </li>
                </form>
            </ul>
            </div>
        </div>
    </div>
    @endrole

    <!-- Dashboard Box -->
    <div class="col-xl-12 max-w-full">
        <form method="post" action="/update_password">
            @csrf
            <div id="test1" class="dashboard-box">

                <!-- Headline -->
                <div class="headline">
                    <h3><i class="icon-material-outline-lock"></i> Password & Security</h3>
                </div>

                <div class="content with-padding">
                    <div class="row max-w-5xl">
                        <div class="col-xl-4">
                            <div class="submit-field">
                                <h5>Current Password</h5>
                                <input type="password" name="current_password" class="with-border">
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="submit-field">
                                <h5>New Password</h5>
                                <input type="password" name="password" class="with-border">
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="submit-field">
                                <h5>Repeat New Password</h5>
                                <input type="password" name="password_confirmation" class="with-border">
                            </div>
                        </div>

                        {{-- <div class="col-xl-12">
                            <div class="checkbox">
                                <input type="checkbox" id="two-step" checked>
                                <label for="two-step"><span class="checkbox-icon"></span> Enable Two-Step Verification via Email</label>
                            </div>
                        </div> --}}

                        <!-- Button -->
                        <div class="col-xl-12">
                            <button type="submit" class="button ripple-effect">Change Password</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

        <!-- Dashboard Box -->
        <div class="col-xl-12 max-w-full">
        <form method="post" action="../update_withdrawal_info">
            @csrf
            <div id="test1" class="dashboard-box">

                <!-- Headline -->
                <div class="headline">
                    <h3><i class="icon-material-outline-lock"></i> Withdrawal Walet</h3>
                </div>

                <div class="content with-padding">
                    <div class="row max-w-5xl">
                            <h2 class="mb-4 col-xl-12">PayPal</h2>
                            <div class="col-xl-6">
                                <div class="submit-field">
                                    <h5>Paypal Email</h5>
                                    <input type="text" name="paypal" class="with-border" value="{{  old('paypal', $user->paypal) }}">
                                </div>
                            </div>

                            <hr class="col-xl-11 mb-4">

                        <!-- <div class="col-xl-6">
                            <div class="submit-field">
                                <h5>Skrill Email</h5>
                                <input type="text" name="skrill" class="with-border" value="{{  old('skrill', $user->skrill) }}">
                            </div>
                        </div> -->

                        <h2 class="mb-4 col-xl-12">Mobile Money</h2>
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Mobile Money Issued Country</h5>
                                    <select class="selectpicker with-border" data-size="7" title="Select Country" data-live-search="true" name="momo_country" id="momo_country">
                                        @foreach ($countries as $country)
                                            @if($country->name == 'Ghana')
                                            <option value="{{ $country->code }}" {{ $user->momo_country == $country->code ? 'selected="selected"' : '' }}>
                                                {{ $country->name }}
                                            </option> 
                                            @endif
                                        @endforeach                                 
                                    </select>
                                </div>
                            </div>
                         
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Mobile Money Network</h5>
                                    <!-- <input type="text" name="momo_network" class="with-border" value="{{  old('momo_network', $user->momo_network) }}"> -->
                                    <select class="selectpicker with-border" data-size="7" title="Select Network" data-live-search="true" name="momo_network" id="momo_network">
                                    <option value="MTN" 
                                    @if($user->momo_network == "MTN") 
                                    selected 
                                    @endif
                                    >MTN</option> 
                                    <option value="VDF"
                                    @if($user->momo_network == "VDF") 
                                    selected 
                                    @endif
                                    >VODAFONE</option> 
                                    <option value="ATL"
                                    @if($user->momo_network == "ATL") 
                                    selected 
                                    @endif
                                    >AIRTEL</option> 
                                    <option value="TGO"
                                    @if($user->momo_network == "TIGO") 
                                    selected 
                                    @endif
                                    >TIGO</option> 
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="submit-field">
                                    <h5>Mobile Money Number</h5>
                                    <input type="text" name="momo" class="with-border" value="{{  old('momo', $user->momo) }}">
                                </div>
                            </div>
                           
                        <hr class="col-xl-11 mb-4">
                        <h2 class="mb-4 col-xl-12">Bank</h2>
                        <div class="col-xl-4">
                            <div class="submit-field">
                                <h5>Account Holder Name</h5>
                                <input type="text" name="bank_account_name" class="with-border" value="{{  old('bank_account_name', $user->bank_account_name) }}">
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="submit-field">
                                <h5>Bank Name</h5>
                                <input type="text" name="bank_name" class="with-border" value="{{  old('bank_name', $user->bank_name) }}">
                            </div>
                        </div>


                        <div class="col-xl-4">
                            <div class="submit-field">
                                <h5>Account Type</h5>
                                <select class="selectpicker with-border" data-size="7" title="Select Account Type" data-live-search="true" name="bank_account_type" id="bank_account_type">
                                    <option value="checking"
                                    @if($user->bank_account_type == "checking") 
                                    selected 
                                    @endif
                                    >Checking</option>
                                    <option value="saving" 
                                    @if($user->bank_account_type == "saving") 
                                    selected 
                                    @endif>Saving</option>
                                    <option value="business"
                                    @if($user->bank_account_type == "business") 
                                    selected 
                                    @endif>Business</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-xl-4">
                            <div class="submit-field">
                                <h5>Bank Account No</h5>
                                <input type="text" name="bank_no" class="with-border" value="{{  old('bank_no', $user->bank_no) }}">
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="submit-field">
                                <h5>Bank Routing Number</h5>
                                <input type="text" name="bank_routing_number" class="with-border" value="{{  old('bank_routing_number', $user->bank_routing_number) }}">
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="submit-field">
                                <h5>Bank Account Branch</h5>
                                <input type="text" name="bank_branch" class="with-border" value="{{  old('bank_branch', $user->bank_branch) }}">
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="submit-field">
                                <h5>Bank Account Country</h5>
                                <select class="selectpicker with-border" data-size="7" title="Select Country" data-live-search="true" name="bank_country" id="bank_country">
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->code }}" {{ $user->bank_country == $country->code ? 'selected="selected"' : '' }}>
                                        {{ $country->name }}
                                    </option> 
                                    @endforeach                                 
                                </select>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="col-xl-12">
                            <button type="submit" class="button ripple-effect">Update Withdrawal Info</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="my-6 col-xl-12">
    <div id="test1" class="dashboard-box">
        <!-- Headline -->
        <div class="headline">
            <h3><i class="icon-line-awesome-money"></i>Billing Information </h3>
            @if(Auth::user()->hasPaymentMethod()) 
            Default Card ({{ $card->brand }})</br> 
            Last four digits: {{ $card->last4 }}
            @endif
        </div>
        <div class="flex flex-row justify-center items-center">
            <div id="spinner" style="display:none" class="spinner-border text-warning w-12 h-12 my-2"></div>
        </div>
        <div class="content with-padding col-xl-6" id="billing">
                <input placeholder="Card Holder Name" value="{{ $user->name }}" class="with-border" id="card-holder-name" type="text">
                <!-- Stripe Elements Placeholder -->
                <div class="mt-4 border rounded p-3" id="card-element"></div>

                <button  class="my-4 button ripple-effect" id="card-button" data-secret="{{ $intent->client_secret }}">
                    Update Payment Method
                </button>
        </div>
    </div>
    </div>
    
    {{-- <!-- Button -->
    <div class="col-xl-12">
        <a href="#" class="button ripple-effect big margin-top-30">Save Changes</a>
    </div> --}}
</div>
@endsection

@push('custom-scripts')
<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script>
    const stripe = Stripe('pk_test_5dfSYbt8bR3wfq8YcleK1YSE00CyBMueNa');

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');

    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;
    

    cardButton.addEventListener('click', async (e) => {
        if(cardHolderName.value == ''){
            return alert('Enter Card Holder Name')
        }
        $('#spinner').show()
        $('#billing').hide()
        const { setupIntent, error } = await stripe.handleCardSetup(
            clientSecret, cardElement, {
                payment_method_data: {
                    billing_details: { name: cardHolderName.value }
                }
            }
        );

        if (error) {
            
            $('#spinner').hide()
            $('#billing').show()
            console.log(error);
            alert(error.message)
            
        } else {
            $('#spinner').hide();
            console.log(setupIntent);
            axios.post('billing/paymentmethod/update',{
                method: setupIntent.payment_method
            }).then(function(r){
                alert(r.data.message);
                console.log(r);
                $('#billing').show()
                window.location.reload();
                
            }).catch(function(e){
                console.log(e);
                alert('Pls, try again')
                $('#billing').show()
                window.location.reload();
                
            })
        }
    });
</script>
    <script>
        $(document).ready(function(){
            $(".skills-dropdown").select2({
              tags: true,
              placeholder: "Choose Skills",
              allowClear: true,
              maximumSelectionLength: 10
            });
            var userCountryCode = "{{ $user->country_id }}";
            if(userCountryCode != ""){
                setCountryCallCode({{ $user->country_id }});
            }
            $('#country').change(function(){
                var selected = $(this).find(":selected").val();

                setCountryCallCode(selected);
            });
        });

        function setCountryCallCode(selectedtCountryId){
            var countries = {!! json_encode($countries) !!};
            var selected = $.grep(countries, function(e){ return e.id == selectedtCountryId; })[0];
            $('.intl_num').text(selected.dial_code);
        }
    </script>
@endpush