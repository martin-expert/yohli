@extends('layouts.master')
@section('title', 'Checkout')
@section('title_bar')
    @include('partials.title_bar')
@endsection

@section('content')

<!-- Container -->
<div class="container">
	<div class="row">
		<div class="col-xl-8 col-lg-8 content-right-offset">
			

			<!-- Hedaline -->
			<h3>Billing Cycle</h3>

			<!-- Billing Cycle Radios  -->
			<form name="checkout" action="../order-confirmation" method="POST">
			@csrf
			<div class="billing-cycle margin-top-25">
			
				<!-- Radio -->
				<div class="radio">
					<input id="radio-5" value="{{ $plan->id }}" name="plan" type="radio" checked>
					<label for="radio-5">
						<span class="radio-label"></span>
						Billed Monthly
						<span class="billing-cycle-details">
							<span class="regular-price-tag">${{ $plan->price}} / month</span>
						</span>
					</label>
				</div>
			
				<!-- Radio -->
				<!-- <div class="radio">
					<input id="radio-6" name="radio-payment-type" type="radio">
					<label for="radio-6"><span class="radio-label"></span>
						Billed Yearly
						<span class="billing-cycle-details">
							<span class="discounted-price-tag">${{ $plan->price * 12 }} / year</span>
						</span>
					</label>
				</div> -->
			</div>
			

			<!-- Hedline -->
			<h3 class="margin-top-50">Payment Method</h3>

			<!-- Payment Methods Accordion -->
			<div class="payment margin-top-30">
			@if(Auth::user()->hasPaymentMethod()) 
				<div class="payment-tab payment-tab-active">
					<div class="payment-tab-trigger">
						<input checked id="paypal" name="cardType" type="radio" value="paypal">
						<label for="paypal">Use Card on file</label>
						<!-- <img class="payment-logo paypal" src="https://i.imgur.com/ApBxkXU.png" alt=""> -->
						<p>
							Type: ({{ $card->brand }})</br> 
            				Last four digits: {{ $card->last4 }}
						</p>
					</div>
					

					<div class="payment-tab-content">
						<p>Use Card on file for subscription</p>
					</div>
				</div>
			@endif

				
				<div class="payment-tab">
					<div class="payment-tab-trigger">
						<input type="radio" name="cardType" id="creditCart" value="creditCard">
						<label for="creditCart">Credit / Debit Card</label>
						<img class="payment-logo" src="https://i.imgur.com/IHEKLgm.png" alt="">
					</div>

					<div class="payment-tab-content">
						<div class="row payment-form-row">
							<div class="flex flex-row justify-center items-center">
							<div id="spinner" style="display:none" class="spinner-border text-warning w-12 h-12 my-2"></div>
						</div>
						<div class="w-full" id="billing">
							<div class="col-md-12">
								<div class="card-label">
									<input id="card-holder-name" name="nameOnCard" value="{{ Auth::user()->name }}" required type="text" placeholder="Cardholder Name">
								</div>
							</div>

							<!-- Stripe Elements Placeholder -->
							<div class="mt-5 col-md-12 text-2xl border rounded p-3" id="card-element"></div>

							<button  class="my-5 mx-1 button bg-blue-500 ripple-effect" id="card-button" data-secret="{{ $intent->client_secret }}">
								Add Payment Method
							</button>
						</div>

						</div>
					</div>
				</div>

			</div>
			<!-- Payment Methods Accordion / End -->
		
			<button type="submit" class="button big ripple-effect margin-top-40 margin-bottom-65">Proceed Payment</botton>
		</div>
		


		<!-- Summary -->
		<div class="col-xl-4 col-lg-4 margin-top-0 margin-bottom-60">
			
			<!-- Summary -->
			<div class="boxed-widget summary margin-top-0">
				<div class="boxed-widget-headline">
					<h3>Summary</h3>
				</div>
				<div class="boxed-widget-inner">
					<ul>
						<li>{{ $plan->title }} Plan<span> ${{ $plan->price  }}</span></li>
						<li>VAT <span>$0.00</span></li>
						<li class="total-costs">Final Price <span>${{ $plan->price  }}</span></li>
					</ul>
				</div>
			</div>
			<!-- Summary / End -->

			<!-- Checkbox -->
			<div class="checkbox margin-top-30">
				<input type="checkbox" id="two-step" required checked/>
				<label for="two-step"><span class="checkbox-icon"></span>  I agree to the <a href="#">Terms and Conditions</a> and the <a href="#">Automatic Renewal Terms</a></label>
			</div>
		</div>
		</form>
	</div>
</div>
<!-- Container / End -->
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
		e.preventDefault();
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
            axios.post('../billing/paymentmethod/update',{
                method: setupIntent.payment_method
            }).then(function(r){
                alert(r.data.message);
                console.log(r);
                $('#billing').show()
                
            }).catch(function(e){
                console.log(e);
                alert('Pls, try again')
                $('#billing').show()
                
            })
        }
    });
</script>
@endpush
