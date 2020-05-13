@extends('layouts.dashboard_master')
@section('title', 'Milestones')
@section('subtitle')
Milestones for <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a> 

@role('freelancer')
@if($job->status != 'completed')
<div class="flex flex-row flex-wrap justify-between">
    <a href="#small-dialog-4"  class="popup-with-zoom-anim  mr-1">
        <span class=" text-sm bg-light-blue-500 shadow px-5 py-2 rounded-full text-white">Add Milestone</span>
    </a>
    <p class="px-4 py-2 bg-red-400 text-white shadow ml-1 mt-2">Ensure You add all milestones before the start of the project.</p>
</div>
@endif
@endrole
@endsection

@section('content')
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">               
                @if(sizeof($milestones) == 0)
                <p class="text-center text-muted">NO MILESTONES FOR THIS JOB</p>
                @else
                <div class="flex flex-row flex-wrap justify-between">
                <h3><i class="icon-material-outline-access-time"></i> {{ $completion }}% Completed</h3>

                <h3><i class="icon-line-awesome-money"></i> {{ $payment }}% Payments</h3>

                </div>
                @endif
            </div>
            {{-- approved, done, change, not done --}}
    
            @if(sizeof($milestones) > 0)
            <div class="content pb-1">
                
                <ul class="timeline">
                    @forelse ($milestones as $milestone)
                    <li class="event {{ $milestone->status == 'done' ? 'done' : 'notdone'}}">
                        @role('freelancer')
                        @if($milestone->status == 'not done')
                        <p class="text-red-500">Start the milestone only when Hirer has approved</p>
                        @elseif($milestone->status == 'request changes')
                        <p class="text-red-500">Hirer has requested for changes</p>
                        @elseif($milestone->status == 'approved')
                        <p class="text-yellow-500">Start work, Hirer has approved your milestone. Funds in escrow. When done, mark as completed</p>
                        @elseif($milestone->status == 'done' & $milestone->is_paid == 0)
                        <p class="text-light-blue-500">Funds will be transfered into your account soon. </p>
                        @elseif($milestone->status == 'done' & $milestone->is_paid == 1)
                        <p class="text-green-500">Funds has been transfered into your account. </p>
                        @endif
                        @endrole
                         <p>{{ $milestone->status }}</p>
                        <h3> <a href="#small-dialog-2"  class="popup-with-zoom-anim milestoneDetails" data-milestone="{{ $milestone }}">
                            {{ $milestone->heading ?? ''}}</a>
                            @if($milestone->status == 'done') 
                                <i class="icon-material-outline-check-circle text-success"></i>
                                @if($milestone->is_paid ?? '')
                                    <span class="badge badge-success">Paid</span>
                                @endif
                            @endif
                            </h3>
                            <div class="flex flex-row flex-wrap justify-between">
                            <p>${{ $milestone->cost ?? ""}}</p>
                            @role('freelancer')
                                @if($milestone->status == 'approved') 
                                <span class="float-right">
                                    <a href="#small-dialog-3"  class="popup-with-zoom-anim button btn-xs completedBtn" data-milestone="{{ $milestone }}">
                                        <i class="icon-material-outline-check-circle"></i> Mark as Completed
                                    </a>
                                @elseif($milestone->status != 'approved' & $milestone->status != 'done')
                                    <a href="#small-dialog-edit"  class="popup-with-zoom-anim button dark btn-xs editBtn" data-milestone="{{ $milestone }}">
                                        <i class="icon-feather-edit"></i> <span class="px-3 text-sm">Edit Milestone</span>
                                    </a>
                                </span>
                                @endif
                            @endrole
                            @role('hirer')
                           
                            <span class="float-right my-2">
                                @if($milestone->status != 'approved' & $milestone->status != 'done') 
                                <a href="#small-dialog-5"  class="popup-with-zoom-anim button btn-xs approvedBtn" data-milestone="{{ $milestone }}">
                                    <i class="icon-material-outline-check-circle"></i> Mark as Approved
                                </a>
                                @endif
                                @if($milestone->status != 'request changes' & $milestone->status != 'done' & $milestone->status != 'approved')
                                <a href="#small-dialog-6"  class="popup-with-zoom-anim button btn-xs changeBtn" data-milestone="{{ $milestone }}">
                                    <i class="icon-material-outline-check-circle"></i> Request Change
                                </a>
                                @endif
                                @if($milestone->status == 'done' & $milestone->is_paid == 0)
                                <a href="#small-dialog-1"  class="popup-with-zoom-anim button btn-xs pay ripple-effect" data-milestone="{{ $milestone }}">
                                    <span class="px-6">Pay</span>
                                </a>
                                @endif
                            </span>
                            @endrole
                           
                        </div>
                       
                    </li>
                    @empty
                    <p class="text-center text-muted py-3">NO MILESTONES FOR THIS JOB</p>
                    @endforelse
                </ul>
               
            </div>
            @endif

        </div>
    </div>
</div>

<!-- Bid Acceptance Popup
================================================== -->
<div id="small-dialog-1" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab1">Release Payment</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3 id="releasePayment">Release Payment to {{ $milestone->name ?? '' }} for Milestone?</h3>
					<div class="bid-acceptance margin-top-15" id="paymentCost">
                    {{ $milestone->cost ?? '' }}
					</div>

				</div>

                <!-- Button -->
                <form action="" method="post" id="releasePaymentForm">
                <input name="transfer_id" type="hidden" value="{{ $milestone->profile_id ?? '' }}"/>
                    @csrf
                </form>

				<button class="margin-top-15 button full-width button-sliding-icon ripple-effect" type="submit" form="releasePaymentForm">Yes, Release <i class="icon-material-outline-check-circle"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Bid Acceptance Popup / End -->

<!-- Bid Acceptance Popup
================================================== -->
<div id="small-dialog-2" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab1">Milestone Details</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
                    <h3 id="milestoneHeading">DD</h3>
                    <div class="bid-acceptance margin-top-15">
                        <p id="milestoneCost"></p>
					</div>
                    <div class="bid-acceptance margin-top-15">
                        <p id="milestoneStatus"></p>
					</div>
					<div class="margin-top-15">
						<p id="milestoneActivity"></p>
					</div>

				</div>

				
			</div>

		</div>
	</div>
</div>
<!-- Bid Acceptance Popup / End -->

<!-- Bid Acceptance Popup
================================================== -->
<div id="small-dialog-3" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab1">Confirm Completion</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>Are you sure you want to mark this as completed?</h3>

				</div>

                <!-- Button -->
                <form action="" method="post" id="completionForm">
                    @csrf
                    <input type="hidden" name="status" value="done">
                </form>

				<button class="margin-top-15 button full-width button-sliding-icon ripple-effect" type="submit" form="completionForm">Yes <i class="icon-material-outline-check-circle"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Bid Acceptance Popup / End -->

<!-- Bid Acceptance Popup
================================================== -->
<div id="small-dialog-4" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab1">Add Milestone</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">

                <!-- Button -->
                <form action="../milestones/add" method="post" id="addMileStoneForm">
                    @csrf
                    <input type="hidden" name="job_id" value="{{ $job->id }}">
                    <input type="hidden" name="user_id" value="{{ $job->user_id }}">
                    <input type="hidden" name="available" value="{{ $available }}">
                    <!-- Price Slider -->
                    <div class="submit-field">
                        <label>Title</label>
                        <input name="heading" placeholder="Domain and Hosting Set up" required value="{{ old('heading') }}">
                    </div>
                    <div class="submit-field">
                        @if($job->budget_type == 'fixed')
                        <label>Cost ( <i class="text-muted">Amount Limit $ <i>{{ $available }}</i></i> )</label>
                        <input name="cost" type="number" required max="{{ $available }}" value="{{ old('cost') }}" id="cost">
                        @else
                        <label>Hrs</label>
                        <input name="hours" type="text"  value="{{ old('hours') }}" id="hours">
                        <label>Cost ( <i class="text-muted">Amount Limit $ <i>{{ $available }}</i></i> )</label>
                        <input name="cost" type="text" required max="{{ $available }}" value="{{ old('cost') }}" id="cost">
                        @endif
                    </div>
                    <div class="submit-field">
                        <label>Description</label>
                        <textarea name="activity" cols="10" placeholder="Milestone Details" class="with-border" required value="{{ old('activity') }}"></textarea>
                    </div>                    
                </form>

				<button class="margin-top-15 button full-width button-sliding-icon ripple-effect" type="submit" form="addMileStoneForm">Continue <i class="icon-material-outline-check-circle"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Bid Acceptance Popup / End -->

<!-- Bid Acceptance Popup
================================================== -->
<div id="small-dialog-5" class="zoom-anim-dialog mfp-hide dialog-with-tabs bg-white max-w-md mx-auto">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab1">Confirm Approved</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>Are you sure you want to mark this as approved?</h3>

				</div>

                <!-- Button -->
                <form action="hirer/update_status/" method="post" id="approvedForm">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                </form>

				<button class="margin-top-15 button full-width button-sliding-icon ripple-effect" type="submit" form="approvedForm">Yes <i class="icon-material-outline-check-circle"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Bid Acceptance Popup / End -->

<!-- Bid Acceptance Popup
================================================== -->
<div id="small-dialog-6" class="zoom-anim-dialog mfp-hide dialog-with-tabs bg-white max-w-md mx-auto">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab1">Confirm Request for a Change</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>Are you sure you want to Request a change</h3>

				</div>

                <!-- Button -->
                <form action="hirer/update_status/" method="post" id="changeForm">
                    @csrf
                    <input type="hidden" name="status" value="request changes">
                </form>

				<button class="margin-top-15 button full-width button-sliding-icon ripple-effect" type="submit" form="changeForm">Yes <i class="icon-material-outline-check-circle"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Bid Acceptance Popup / End -->

<!-- Bid Acceptance Popup
================================================== -->
<div id="small-dialog-edit" class="zoom-anim-dialog mfp-hide dialog-with-tabs bg-white max-w-md mx-auto">
	<!--Tabs -->
	<div class="sign-in-form">

	<ul class="popup-tabs-nav">
			<li><a href="#tab1">Edit Milestone</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">

                <!-- Button -->
                <form action="#" method="post" id="editMileStoneForm">
                    @csrf
                    <input type="hidden" name="job_id" value="{{ $job->id }}">
                    <input type="hidden" name="user_id" value="{{ $job->user_id }}">
                    <input type="hidden" name="available" value="{{ $available }}">
                    <!-- Price Slider -->
                    <div class="submit-field">
                        <label>Title</label>
                        <input name="heading" placeholder="Domain and Hosting Set up" required value="{{ old('heading') }}" id="heading">
                    </div>
                    <div class="submit-field">
                        @if($job->budget_type == 'fixed')
                        <label>Cost ( <i class="text-muted">Amount Limit $ <i id="availableIsh"></i></i> )</label>
                        <input name="cost" type="number" required max="{{ $available }}" value="{{ old('cost') }}" id="update_cost">
                        @else
                        <label>Hrs</label>
                        <input name="hours" type="text"  value="{{ old('hours') }}" id="update_hours">
                        <label>Cost ( <i class="text-muted">Amount Limit $ <i id="availableIsh"></i></i> )</label>
                        <input name="cost" type="text" required max="{{ $available }}" value="{{ old('cost') }}" id="update_cost" readonly>
                        @endif
                    </div>
                    <div class="submit-field">
                        <label>Description</label>
                        <textarea name="activity" cols="10" placeholder="Milestone Details" class="with-border" required value="{{ old('activity') }}" id="activity"></textarea>
                    </div>                    
                </form>

				<button class="margin-top-15 button full-width button-sliding-icon ripple-effect" type="submit" form="editMileStoneForm">Continue <i class="icon-material-outline-check-circle"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Bid Acceptance Popup / End -->
@endsection

@push('custom-scripts')
    <script>
        $(document).ready(function(){
            
            var bid = <?= json_encode($job->accepted_bid); ?>;
            var cost;

            $("#hours").on("change paste keyup", function() {
                cost = $(this).val() * bid.rate
                $("#cost").val(cost);
            });

            $("#update_hours").on("change paste keyup", function() {
                cost = $(this).val() * bid.rate
                $("#update_cost").val(cost);
            });

            $('.milestoneDetails').click(function(){
                var _milestone = $(this).attr("data-milestone");
                var milestone = JSON.parse(_milestone);

                $('#milestoneHeading').text(milestone.heading);
                $('#milestoneActivity').text(milestone.activity);
                $('#milestoneStatus').text("Status: " + milestone.status);
                $('#milestoneCost').text('$'+ThousandSeparator(milestone.cost));
            });

            $('.editBtn').click(function(){
                var _milestone = $(this).attr("data-milestone");
                var milestone = JSON.parse(_milestone);
                var available = {!! $available !!};

                $('#heading').val(milestone.heading);
                $('#activity').val(milestone.activity);
                $('#update_cost').val(milestone.cost);
                $('#update_hours').val(milestone.hours);
                $('#update_cost').attr('max', milestone.cost + available);
                $('#availableIsh').text(milestone.cost + available);
                $('#editMileStoneForm').attr('action', 'update_milestone/'+milestone.uuid);
            });

            $('.pay').click(function(){
                var _milestone = $(this).attr("data-milestone");
                var milestone = JSON.parse(_milestone);

                $('#releasePayment').text('Release Payment to '+ milestone.profile.name + ' for Milestone?');
                $('#paymentCost').text('$'+ThousandSeparator(milestone.cost));
                $('#releasePaymentForm').attr('action', 'release_payment/'+milestone.uuid);
            });

            $('.completedBtn').click(function(){
                var _milestone = $(this).attr("data-milestone");
                var milestone = JSON.parse(_milestone);
                $('#completionForm').attr('action', 'freelancer/update_status/'+milestone.uuid);
            });

            $('.approvedBtn').click(function(){
                var _milestone = $(this).attr("data-milestone");
                var milestone = JSON.parse(_milestone);
                $('#approvedForm').attr('action', 'hirer/update_status/'+milestone.uuid);
            });

            $('.changeBtn').click(function(){
                var _milestone = $(this).attr("data-milestone");
                var milestone = JSON.parse(_milestone);
                $('#changeForm').attr('action', 'hirer/update_status/'+milestone.uuid);
            });

            function ThousandSeparator(nStr) {
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