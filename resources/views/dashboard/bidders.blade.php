@extends('layouts.dashboard_master')
@section('title', 'Bidders')
@section('subtitle')
Bidders for <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->name }}</a>
@endsection

@section('content')
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">

            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-supervisor-account"></i> {{ sizeof($bids) }} Bidders 
                    @if($job->status == 'assigned')
                    <span class="badge badge-success">Job Assigned</span>
                    @endif
                </h3>
                {{-- <div class="sort-by">
                    <select class="selectpicker hide-tick">
                        <option>Highest First</option>
                        <option>Lowest First</option>
                        <option>Fastest First</option>
                    </select>
                </div> --}}
            </div>

            <div class="content">
                <ul class="dashboard-box-list">
                    @forelse ($bids as $bid)
                    @php
                        //dd($freelancer);
                        $user = \App\Models\User::where('id', $bid->profile->user_id)->first();
                        $my_plan = $user->plan()->first();
                        $badge = '';// Default to 'free'
                        /*$badge = '<img src="'.asset('assets/images/bronze.png').'" alt="" class="badge-icon" />';*/
                        if($my_plan)
                            switch($my_plan->plan_id):
                                /*case 'economy-plus':
                                    $badge = '<img src="'.asset('assets/images/bronze.png').'" alt="" class="badge-icon"/>';
                                    break;*/
                                case 'business':
                                    $badge = '<img src="'.asset('assets/images/silver-white.png').'" alt="" class="badge-icon silver-badge"/>';
                                    break;
                                case 'first-class':
                                    $badge = '<img src="'.asset('assets/images/silver-white.png').'" alt="" class="badge-icon gold-badge"/>';
                                    break;
                            endswitch
                    @endphp
                   <li>
                        <!-- Overview -->
                        <div class="freelancer-overview manage-candidates">
                            <div class="freelancer-overview-inner">

                                <!-- Avatar -->
                                
                                <div class="freelancer-avatar user-badge">
                                    @if($bid->profile->verified)
                                    <div class="verified-badge"></div>
                                    @endif
                                    @if (sizeof($bid->profile->getMedia('profile')) == 0)
                                    <a href="../freelancers/{{ $bid->profile->uuid }}"><img src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt=""></a>
                                    @else
                                    <a href="../freelancers/{{ $bid->profile->uuid }}"><img src="{{ $bid->profile->getFirstMediaUrl('profile', 'big') }}" alt=""/></a>
                                    @endif
                                    {!!$badge!!}
                                </div>


                                <!-- Name -->
                                <div class="freelancer-name">
                                    <h4>
                                        <a href="../freelancers/{{ $bid->profile->uuid }}">{{ $bid->profile->name ?? '' }} 
                                            <img class="flag" src="{{ asset('assets/images/flags/'. strtolower($bid->profile->country->code).'.svg') }}" alt="{{ $bid->profile->country->name }}">
                                        </a>
                                    </h4>

                                    <!-- Details -->
                                    <span class="freelancer-detail-item">
                                        <!-- <a href="mailto:{{ $bid->profile->email }}"><i class="icon-feather-mail"></i> {{ $bid->profile->headline  ?? ''}}</a></span> -->

                                    <!-- Rating -->

                                    @if($bid->profile->rating == 0 || $bid->profile->rating == null)
                                    <span class="company-not-rated">Minimum of 1 vote required</span>
                                    @else
                                    <div class="freelancer-rating">
                                        <div class="star-rating" data-rating="{{ $bid->profile->rating }}"></div>
                                    </div>
                                    @endif

                                    <!-- Bid Details -->
                                    <ul class="dashboard-task-info bid-info">
                                        <li><strong>${{ $bid->rate  ?? ''}}</strong><span class="text-capitalize">{{ $job->budget_type  ?? ''}} Rate</span></li>
                                        <li><strong>{{ $bid->delivery_time  ?? ''}} {{ $bid->delivery_type  ?? ''}}</strong><span>Delivery Time</span></li>
                                    </ul>

                                    <!-- Buttons -->
                                    @if($job->status != 'assigned')
                                    <div class="buttons-to-right always-visible margin-top-25 margin-bottom-0">
                                        <a href="#small-dialog-1"  class="popup-with-zoom-anim button ripple-effect acceptBids" data-bid="{{ $bid }}"><i class="icon-material-outline-check"></i> Accept Offer</a>
                                        <a href="#small-dialog-2" class="popup-with-zoom-anim button dark ripple-effect viewBid" data-bid="{{ $bid }}"><i class="icon-feather-eye"></i> View Bid</a>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li> 
                    @empty
                        
                    @endforelse
                   

                </ul>
            </div>
        </div>
    </div>
</div>


<!-- Bid Acceptance Popup
================================================== -->
<div id="small-dialog-1" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab1">Accept Offer</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3 id="acceptBidText">Accept Offer From {{ $bid->profile->name ?? '' }} </h3>
					<div class="bid-acceptance margin-top-15" id="bidPrice">
						${{ $bid->rate ?? '' }} 
					</div>

				</div>

				<form id="acceptBidForm" action="" method="post">
                    @csrf
					{{-- <div class="radio">
						<input id="radio-1" name="radio" type="radio" required>
						<label for="radio-1"><span class="radio-label"></span>  I accept the bid</label>
                    </div> --}}
                    <input type="hidden" name="profile_id" id="aProfileId">
				</form>

				<!-- Button -->
				<button class="margin-top-15 button full-width button-sliding-icon ripple-effect" type="submit" form="acceptBidForm">Accept <i class="icon-material-outline-arrow-right-alt"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Bid Acceptance Popup / End -->


<!-- Send Direct Message Popup
================================================== -->
<div id="small-dialog-2" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab2">View Bid</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab2">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
                <h3 id="view_name" class="py-2"> </h3>
                <span class="px-4 py-3 bg-orange-600 text-white shadow-md rounded" id="view_rate"> </span>
                <p class="text-left pt-2" id="view_body"> </p>
				</div>
					
				
				
				<!-- Button -->

			</div>

		</div>
	</div>
</div>
@endsection
@push('custom-scripts')
    <script>
        $(document).ready(function(){
            $('.acceptBids').click(function(){
                var _bid = $(this).attr("data-bid");
                var bid = JSON.parse(_bid);
                
                $('#bidPrice').text('$'+ThousandSeparator2(bid.rate));
                $('#acceptBidText').text('Accept Offer From '+bid.profile.name);
                $('#aProfileId').val(bid.profile.id);
                $('#acceptBidForm').attr('action', 'accept_bid/'+bid.uuid);
            });

            $('.viewBid').click(function(){
                var _bid = $(this).attr("data-bid");
                var bid = JSON.parse(_bid);
                
                $('#view_name').text(bid.profile.name);
                $('#view_rate').text('Rate $'+bid.rate);
                $('#view_body').text(bid.description);
            });
            function ThousandSeparator2(nStr) {
                nStr += '';
                var x = nStr.split('.');
                var x1 = x[0];
                var x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                return x1 + x2;
            }
        });
    </script>
@endpush

