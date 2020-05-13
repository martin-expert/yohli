@extends('layouts.dashboard_master')
@section('title', 'Subscription')

@section('content')
	<div class="row">

		<div class="col-xl-12">
			@auth
			<!-- Billing Cycle  -->
			<div class="margin-bottom-70 flex flex-row flex-wrap justify-start">
			@isset($my_plan->pivot)
				<div class="flex flex-row ">
					<div class="m-1 px-4 pt-3 pb-0 mb-0 bg-orange-500 shadow text-white rounded-lg"><p class="text-white ">{{ $my_plan->title ?? '' }} - Bids {{ $my_plan->quantity ?? '' }}</p></div>
					<div class="m-1 px-4 pt-3 pb-0 mb-0 bg-orange-500 shadow text-white rounded-lg"><p class="text-white">Bids Used: {{ $my_plan->pivot->count ?? '' }}</p></div>
				</div>
				@if($my_plan->plan_id != 'free')
				<div class=""><a onclick="cancel()" href="#"><button class="m-1 bg-light-blue-500 shadow text-white px-16 py-3 font-bold rounded-full">CANCEL</button></a></div>
				@endif
			</div>
			<p>10 free bids are added to your subscriptions every month by default</p>
			<ul>
			<li>Free Plan - 10 free bids</li>
			<li>Economy Plus Plan - 10 additional bids + 10 free bids</li>
			<li>Business Plan - 20 additional bids + 10 free bids</li>
			<li>First Class Plan - 30 additional bids + 10 free bids</li>
			</ul>
			<p>Unused Bids for any plan are removed at that end of each month</p>
			@endisset
			@empty($my_plan->pivot)
			<p>You are not subscribe to any plans yet.. <a href="../pricing">Subscibe now</a>
			@endempty
			@endauth

			


		</div>

	</div>


<div class="margin-top-80"></div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>

function cancel()
{
	Swal.fire({
	title: 'Are you sure?',
	text: "Your Plan and remaining bids will be removed!",
	icon: 'warning',
	showCancelButton: true,
	confirmButtonColor: '#3085d6',
	cancelButtonColor: '#d33',
	confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
	if (result.value) {
		window.location = "/cancel/subscription";
	}
	})
	
}

</script>
@endsection