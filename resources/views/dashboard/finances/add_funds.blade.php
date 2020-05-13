@extends('layouts.dashboard_master')
@section('title', 'Add Funds')

@section('content')
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-6">
        <div class="flex flex-row justify-center items-center">
            <div id="spinner" style="display:none" class="spinner-border text-warning w-12 h-12 my-2"></div>
        </div>
        <div id="billing" class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline flex flex-row justify-between">
                <h3>
                    <i class="icon-line-awesome-money"></i> 
                    <img src="{{ asset('assets/images/stripe-logo.png') }}" height="30px">
                </h3>
                <h3>Balance: ${{ $account->balance }}</h3>
            </div>
    
            <div class="content px-4 py-4">
                <div class="col-md-12">
                    <div class="card-label">
                        <input id="card-holder-name" name="nameOnCard" value="{{ Auth::user()->name }}" required type="text" placeholder="Cardholder Name">
                    </div>
                </div>

                <!-- Stripe Elements Placeholder -->
                <div class="mt-5 col-md-12 text-2xl border rounded p-3" id="card-element"></div>

                <div class="row pt-5">
                    <div class="col-xl-12">
                        <div class="submit-field">
                            <div class="row">
                                <div class="col-xl-4 my-2">
                                    <select class="selectpicker with-border" id="currency">
                                        <option>USD</option>
                                    </select>
                                </div>
                                <div class="col-xl-8 my-2">
                                    <input id="amount" type="number" class="with-border" value="0" required>
                                    <small class="text-muted">Processing fee <span id="processing_fee" class="float-right"></span></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-12"> 
                        <p>Total <b><span id="total" class="float-right"></span></b></p>
                    </div>
                    <div class="col-12">
                        <button  class="my-5 mx-1 button bg-blue-500 ripple-effect" id="card-button" data-secret="{{ $intent->client_secret }}">
                            <i class="icon-material-outline-check-circle mr-1"></i>
                            <span id="amount-value">Confirm & Add $0</span>
                        </button>
                    </div> 
                </div>
            </div>

        </div>
    </div>
    <div class="col-xl-6">
        <h2>Transaction Fees</h2>
        <ul>
        <li>Credit cards, PayPal, Skrill – 2.5%</li> 
        <li>Local bank deposit – Free</li> 
        <li>International wire - $15</li>
        </ul>
    </div>
</div>
@endsection
@push('custom-scripts')
<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script>

    var payment_provider = <?= json_encode($payment_provider); ?>;
    var deposit_rate = Number(payment_provider.deposit_rate);

    const stripe = Stripe('pk_test_5dfSYbt8bR3wfq8YcleK1YSE00CyBMueNa');

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');

    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    const amount = document.getElementById('amount');

    amount.addEventListener('input',updateValue)

    function updateValue(e){
         var total = document.getElementById('total');
         var perc = Number((deposit_rate / 100) * e.target.value);
         var processing_fee = document.getElementById('processing_fee');
         processing_fee.innerHTML = "$"+perc
         total.innerHTML = Number(Number(e.target.value) + perc);
        //  console.log();
         
         
         var amountValue = document.getElementById('amount-value')
         amountValue.innerHTML = "Confirm & Add $" + total.innerHTML;
        //  console.log(amountValue.innerHTML);
    }

    cardButton.addEventListener('click', async (e) => {

        const amount = document.getElementById('amount').value;
        var perc = Number((deposit_rate / 100) * amount);

        function addMoney(method){
            axios.post('add-funds',{
                    method: method,
                    amount: amount,
                    percentage: perc,
                    deposit: 'deposit',
                    type: 'deposit'
                }).then(function(r){
                    alert(r.data.message);
                    console.log(r);
                    window.location.reload();
                    $('#billing').show()
                }).catch(function(e){
                    console.log(e);
                    alert('Pls, try again')
                    $('#billing').show()
                    window.location.reload();

                })
        }

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
            alert(error.message);           
            window.location.reload();

            
        } else {
            $('#spinner').hide();
            console.log(setupIntent);
            axios.post('billing/paymentmethod/update',{
                method: setupIntent.payment_method
            }).then(function(r){
                addMoney(setupIntent.payment_method); 
            }).catch(function(e){
                console.log(e);
                alert('Pls, try again')
                $('#billing').show()
                window.location.reload();
                
            })
        }
    });

  
</script>
@endpush
