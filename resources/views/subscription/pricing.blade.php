@extends('layouts.master')

<?php
	$userpro = Auth::user()->profile()->first();
?>
@if($userpro->type =='freelancer')
@section('title', 'Pricing Plans')
@section('title_bar')
    @include('partials.title_bar')
@endsection

@section('content')

<div class="container">
	<div class="row">

		<div class="col-xl-12">
			@auth
			<!-- Billing Cycle  -->
			@isset($my_plan->pivot)
			<div class="billing-cycle-radios margin-bottom-70 flex flex-row justify-end">
				<div class="mx-3 px-4 py-3 bg-orange-500 shadow-lg text-white"><h4 class="text-white">Current Plan: {{ $my_plan->title ?? '' }} - Bids {{ $my_plan->quantity ?? '' }}</h4></div>
				<div class="mx-3 px-4 py-3 bg-orange-500 shadow-lg text-white"><h4 class="text-white">Bids Used: {{ $my_plan->pivot->count ?? '' }}</h4></div>
			</div>
			@endisset
			@endauth

			<!-- Pricing Plans Container -->
			<div class="pricing-plans-container pt-10">

				@foreach($plans as $plan)
				@if($plan->title != 'Free')
					<!-- Plan -->
					<div class="pricing-plan {{ $plan->recommended == 1 ? 'recommended' : '' }}">
						@if($plan->recommended == 1)
						<div class="recommended-badge">Recommended</div>
						@endif
						<h3>{{ $plan->title }}</h3>
						<div class="pricing-plan-label billed-monthly-label"><strong>${{ $plan->price }}</strong>/ monthly</div>
						<!-- <div class="pricing-plan-label billed-yearly-label"><strong>$529</strong>/ yearly</div> -->
						<div class="pricing-plan-features">
							<strong>Features of {{ $plan->title }}</strong>
							<p>{{ $plan->quantity }} Bids Each Month</p>
							<p>{!! $plan->description !!}</p>
						</div>
						<a href="{{ route('checkout', $plan->id) }}" class="button full-width margin-top-20">Buy Now</a>
					</div>
				@endif
				@endforeach

			</div>


		</div>

	</div>
</div>


<div class="margin-top-80"></div>
@endsection
