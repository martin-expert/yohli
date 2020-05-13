@extends('layouts.dashboard_master')
@section('title', 'My Active Bids')

@section('content')
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">

            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-gavel"></i> Bids List</h3>
            </div>

            <div class="content">
                <ul class="dashboard-box-list">
                    @forelse ($bids as $bid)
                    <li>
                            <!-- Job Listing -->
                            <div class="job-listing width-adjustment">
    
                                <!-- Job Listing Details -->
                                <div class="job-listing-details">
    
                                    <!-- Details -->
                                    <div class="job-listing-description">
                                        <h3 class="job-listing-title"><a href="{{ route('jobs.show', $bid->job->slug) }}">{{ $bid->job->title }}</a></h3>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Task Details -->
                            <ul class="dashboard-task-info">
                                <li><strong>${{ $bid->rate }}</strong><span class="text-capitalize">{{ $bid->job->budget_type }} Rate</span></li>
                                <li><strong>{{ $bid->delivery_time }} {{ $bid->delivery_type }}</strong><span>Delivery Time</span></li>
                            </ul>
    
                           
        
                            <!-- Buttons -->
                            <div class="buttons-to-right always-visible">
                                <a href="#small-dialog" data-bid="{{ $bid }}" class="edit_bid popup-with-zoom-anim button dark ripple-effect ico" title="Edit Bid" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                                <a href="/delete_bid/{{ $bid->uuid }}" onclick="if(confirm('Are you sure you want to delete?')){event.preventDefault();
								document.getElementById('delete_bid_form').submit();}"  class="button red ripple-effect ico" title="Cancel Bid" data-tippy-placement="top">
									<i class="icon-feather-trash-2"></i>
								</a>

								<form id="delete_bid_form" action="/delete_bid/{{ $bid->uuid }}" method="POST" style="display: none;">
                            	    @csrf
                                </form>
                            </div>
                        </li> 
                    @empty
                        <p class="text-center text-muted py-3">YOU HAVE NO BIDS CURRENTLY</p>
                    @endforelse

                </ul>
            </div>
        </div>
    </div>
</div>








<!-- Edit Bid Popup
================================================== -->
<div id="small-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab">Edit Bid</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">

				<form method="post" action="/edit_bid/{{ $bid->uuid ?? ''}}">
					@csrf
					<input type="hidden" name="id" id="bid_id">	
				<!-- Bidding -->
				<div class="bidding-widget">
						<!-- Headline -->
						<span class="bidding-detail">Set your <strong>minimal hourly rate</strong></span>

						<!-- Price Slider -->
						<div class="bidding-value">$<span id="biddingVal"></span></div>
						<input name="rate" id="bid_price" class="bidding-slider" type="text" value="300" data-slider-handle="custom" data-slider-currency="$" data-slider-min="10" data-slider-max="1000" data-slider-value="90" data-slider-step="1" data-slider-tooltip="hide" />
						
						<!-- Headline -->
						<span class="bidding-detail margin-top-30">Set your <strong>delivery time</strong></span>

						<!-- Fields -->
						<div class="bidding-fields">
							<div class="bidding-field">
								<!-- Quantity Buttons -->
								<div class="qtyButtons with-border">
									<div class="qtyDec"></div>
									<input type="text" name="delivery_time" id="delivery_time">
									<div class="qtyInc"></div>
								</div>
							</div>
							<div class="bidding-field">
								<select class="selectpicker default with-border" name="delivery_type" id="delivery_type">
									<option value="days" selected>Days</option>
									<option value="hours">Hours</option>
								</select>
							</div>
						</div>
				</div>
				
				<!-- Button -->
				<button type="submit" class="button full-width button-sliding-icon ripple-effect" type="submit">Save Changes <i class="icon-material-outline-arrow-right-alt"></i></button>
				</form>
			</div>

		</div>
	</div>
</div>
<!-- Edit Bid Popup / End -->

@endsection

@push('custom-scripts')
<script>
		$('.edit_bid').click(function(){
			var _bid = $(this).attr("data-bid");
			var bid = JSON.parse(_bid);
			var maxBudget = parseFloat(bid.job.max_budget);
			var minBudget = parseFloat(bid.job.min_budget);
			var deliveryTime = bid.delivery_time;
			var deliveryType = bid.delivery_type;
			var rate = bid.rate;

			// console.log(bid.job);
			$('#delivery_type').val(deliveryType);
			$('#delivery_time').val(deliveryTime);
			$('#bid_id').val(bid.id);

			var bidPrice = $('#bid_price');
			bidPrice.val(parseInt(rate));
			bidPrice.slider('setAttribute', 'min', minBudget);
			bidPrice.slider('setAttribute', 'max', maxBudget);
			$("#biddingVal").text(ThousandSeparator2(parseInt(rate)));
			$("#biddingVal").text(ThousandSeparator2(parseInt($('.bidding-slider').val())));
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
	</script>
@endpush