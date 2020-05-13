@extends('layouts.dashboard_master')
@section('title', 'Reviews')

@section('content')







<!-- Row -->
<div class="row">

        @role('freelancer')
        <!-- Dashboard Box -->
        <div class="col-xl-6">
            <div class="dashboard-box margin-top-0">

                <!-- Headline -->
                <div class="headline">
                    <h3><i class="icon-material-outline-business"></i> Rate Hirers</h3>
                </div>

                <div class="content">
                    <ul class="dashboard-box-list">
                        @forelse ($jobs as $job)
                        <li>
                            <div class="boxed-list-item">
                                <!-- Content -->
                                <div class="item-content">
								<h4>{{ $job->title }}</h4>
								<p class="mt-2">{{ $job->owner->name }}</p>
								@if(sizeof($job->reviews) == 0)
								<span class="company-not-rated margin-bottom-5">Not Rated</span>
								@else
								<div class="item-details margin-top-10">
									<div class="star-rating" data-rating="{{ $job->rating ?? 0 }}"></div>
									<div class="detail-item"><i class="icon-material-outline-date-range"></i> {{ $job->reviews()->where('reviewable_id', $job->id)->first()->created_at }}</div>
								</div>
								<div class="item-description">
									<p>{{ $job->reviews()->where('reviewable_id', $job->id)->first()->body }}</p>
								</div>
								@endif
                                </div>
                            </div>

                            @if(sizeof($job->reviews) == 0)
                            <a href="#small-dialog-2" class="popup-with-zoom-anim button ripple-effect margin-top-5 margin-bottom-10 leaveReview" data-job="{{ $job }}"><i class="icon-material-outline-thumb-up"></i> Leave a Review</a>
                            @else
                            {{-- <a href="#small-dialog-1" class="popup-with-zoom-anim button gray ripple-effect margin-top-5 margin-bottom-10 editReview" data-job="{{ $job }}"><i class="icon-feather-edit"></i> Edit Review</a> --}}
                            @endif
                        </li> 
                        @empty
                            <p class="text-center py-3">No Reviews</p>
                        @endforelse
                    </ul>
                </div>
            </div>

             <!-- Pagination -->
             <div class="clearfix"></div>
             {{ $jobs->links('vendor.pagination.default') }}
             <div class="clearfix"></div>
             <!-- Pagination / End -->

        </div>
        @endrole

        @role('hirer')
        <!-- Dashboard Box -->
        <div class="col-xl-6">
            <div class="dashboard-box margin-top-0">

                <!-- Headline -->
                <div class="headline">
                    <h3><i class="icon-material-outline-face"></i> Rate Freelancers</h3>
                </div>

                <div class="content">
                    <ul class="dashboard-box-list">
                        @forelse ($jobs as $job)
                        <li>
                            <div class="boxed-list-item">
                                <!-- Content -->
                                <div class="item-content">
								<h4><i  class="icon-feather-briefcase"></i> {{ $job->title ?? ''}}</h4>
								@isset($job->profile)
								<p class="mt-2"><i  class="icon-feather-user"></i> {{ $job->profile->name ?? ''}}</p>
									@if($job->reviewed)
									<div class="item-details margin-top-10">
										<div class="star-rating" data-rating="{{ $job->profile->rating ?? 0 }}"></div>
										<div class="detail-item"><i class="icon-material-outline-date-range"></i> {{ $job->reviews()->where('reviewable_id', $job->id)->first()->created_at ?? ''}}</div>
									</div>
									<div class="item-description">
										<p>{{ $job->reviews()->where('reviewable_id', $job->id)->first()->body ?? ''}}</p>
									</div>
									@else
									<span class="company-not-rated margin-bottom-5">Not Rated</span>
									@endif
								@endisset
                                </div>
                            </div>
							@if(!empty($job->profile))
								@if($job->reviewed)
								{{-- <a href="#small-dialog-1" class="popup-with-zoom-anim button gray ripple-effect margin-top-5 margin-bottom-10 editReview" data-job="{{ $job }}"><i class="icon-feather-edit"></i> Edit Review</a> --}}
								@else
								<a href="#small-dialog-3" class="popup-with-zoom-anim button ripple-effect margin-top-5 margin-bottom-10 leaveReview2" data-job="{{ $job }}"><i class="icon-material-outline-thumb-up"></i> Leave a Review</a>
								@endif

							@else
							<a href="#small-dialog-3" class="popup-with-zoom-anim button ripple-effect margin-top-5 margin-bottom-10 leaveReview2" data-job="{{ $job }}"><i class="icon-material-outline-thumb-up"></i> Leave a Review</a>	
							@endif
							
                        </li> 
                        @empty
						<p class="text-center py-3">No Reviews</p>
                        @endforelse
                    </ul>
                </div>
            </div>

             <!-- Pagination -->
             <div class="clearfix"></div>
             {{ $jobs->links('vendor.pagination.default') }}
             <div class="clearfix"></div>
             <!-- Pagination / End -->

        </div>
        @endrole


    </div>
    <!-- Row / End -->



<!-- Edit Review Popup
================================================== -->
<div id="small-dialog-1" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab1">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>Change Review</h3>
					<span>Rate <a href="#">Herman Ewout</a> for the project <a href="#">WordPress Theme Installation</a> </span>
				</div>
					
				<!-- Form -->
				<form method="post" id="change-review-form">

					<div class="feedback-yes-no">
						<strong>Was this delivered on budget?</strong>
						<div class="radio">
							<input id="radio-rating-1" name="radio" type="radio" checked>
							<label for="radio-rating-1"><span class="radio-label"></span> Yes</label>
						</div>

						<div class="radio">
							<input id="radio-rating-2" name="radio" type="radio">
							<label for="radio-rating-2"><span class="radio-label"></span> No</label>
						</div>
					</div>

					<div class="feedback-yes-no">
						<strong>Was this delivered on time?</strong>
						<div class="radio">
							<input id="radio-rating-3" name="radio2" type="radio" checked>
							<label for="radio-rating-3"><span class="radio-label"></span> Yes</label>
						</div>

						<div class="radio">
							<input id="radio-rating-4" name="radio2" type="radio">
							<label for="radio-rating-4"><span class="radio-label"></span> No</label>
						</div>
					</div>

					<div class="feedback-yes-no">
						<strong>Your Rating</strong>
						<div class="leave-rating">
							<input type="radio" name="rating" id="rating-1" value="1" checked/>
							<label for="rating-1" class="icon-material-outline-star"></label>
							<input type="radio" name="rating" id="rating-2" value="2"/>
							<label for="rating-2" class="icon-material-outline-star"></label>
							<input type="radio" name="rating" id="rating-3" value="3"/>
							<label for="rating-3" class="icon-material-outline-star"></label>
							<input type="radio" name="rating" id="rating-4" value="4"/>
							<label for="rating-4" class="icon-material-outline-star"></label>
							<input type="radio" name="rating" id="rating-5" value="5"/>
							<label for="rating-5" class="icon-material-outline-star"></label>
						</div><div class="clearfix"></div>
					</div>

					<textarea class="with-border" placeholder="Comment" name="message" id="message" cols="7">Excellent programmer - helped me fixing small issue.</textarea>

				</form>
				
				<!-- Button -->
				<button class="button full-width button-sliding-icon ripple-effect" type="submit" form="change-review-form">Save Changes <i class="icon-material-outline-arrow-right-alt"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Edit Review Popup / End -->


<!-- Leave a Review for Freelancer Popup
================================================== -->
<div id="small-dialog-2" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab2">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>Leave a Review</h3>
					<span>Rate <a href="" id="job_link"><span id="job_name"></span></a> </span>
				</div>
					
				<!-- Form -->
				<form method="post" action="" id="leaveReviewForm">
                    @csrf

					<div class="feedback-yes-no">
						<strong>Your Rating</strong>
						<div class="leave-rating">
							<input type="radio" name="rating" id="rating-radio-1" value="5" required>
							<label for="rating-radio-1" class="icon-material-outline-star"></label>
							<input type="radio" name="rating" id="rating-radio-2" value="4" required>
							<label for="rating-radio-2" class="icon-material-outline-star"></label>
							<input type="radio" name="rating" id="rating-radio-3" value="3" required>
							<label for="rating-radio-3" class="icon-material-outline-star"></label>
							<input type="radio" name="rating" id="rating-radio-4" value="2" required>
							<label for="rating-radio-4" class="icon-material-outline-star"></label>
							<input type="radio" name="rating" id="rating-radio-5" value="1" required>
							<label for="rating-radio-5" class="icon-material-outline-star"></label>
						</div><div class="clearfix"></div>
                    </div>
                    <input type="hidden" name="job_id" id="job_id">

					<textarea class="with-border" placeholder="Comment" name="body" cols="7"></textarea>

				</form>
				
				<!-- Button -->
				<button class="button full-width button-sliding-icon ripple-effect" type="submit" form="leaveReviewForm">Leave a Review <i class="icon-material-outline-arrow-right-alt"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Leave a Review Popup / End -->

<!-- Leave a Review for Freelancer Popup
================================================== -->
<div id="small-dialog-3" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab2">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>Leave a Review</h3>
					<span>Rate <a href="" id="profile_link"></a> for the project <a href="" id="job_link2"></a> </span>
				</div>
					
				<!-- Form -->
				<form method="post" action="" id="leaveReviewForm2">
                    @csrf

                    <div class="feedback-yes-no">
						<strong>Was this delivered on budget?</strong>
						<div class="radio">
							<input id="onbudget-radio-1" name="onbudget" type="radio" required checked value="yes">
							<label for="onbudget-radio-1"><span class="radio-label"></span> Yes</label>
						</div>

						<div class="radio">
							<input id="onbudget-radio-2" name="onbudget" type="radio" required value="no">
							<label for="onbudget-radio-2"><span class="radio-label"></span> No</label>
						</div>
					</div>

					<div class="feedback-yes-no">
						<strong>Was this delivered on time?</strong>
						<div class="radio">
							<input id="ontime-radio-1" name="ontime" type="radio" required checked value="yes">
							<label for="ontime-radio-1"><span class="radio-label"></span> Yes</label>
						</div>

						<div class="radio">
							<input id="ontime-radio-2" name="ontime" type="radio" required value="no">
							<label for="ontime-radio-2"><span class="radio-label"></span> No</label>
						</div>
					</div>

					<div class="feedback-yes-no">
						<strong>Your Rating</strong>
						<div class="leave-rating">
							<input type="radio" name="rating" id="rating-radio-1" value="5" required>
							<label for="rating-radio-1" class="icon-material-outline-star"></label>
							<input type="radio" name="rating" id="rating-radio-2" value="4" required>
							<label for="rating-radio-2" class="icon-material-outline-star"></label>
							<input type="radio" name="rating" id="rating-radio-3" value="3" required>
							<label for="rating-radio-3" class="icon-material-outline-star"></label>
							<input type="radio" name="rating" id="rating-radio-4" value="2" required>
							<label for="rating-radio-4" class="icon-material-outline-star"></label>
							<input type="radio" name="rating" id="rating-radio-5" value="1" required>
							<label for="rating-radio-5" class="icon-material-outline-star"></label>
						</div><div class="clearfix"></div>
                    </div>
                    <input type="hidden" name="job_id" id="job_id2">
                    <input type="hidden" name="user_id" id="user_id">

					<textarea class="with-border" placeholder="Comment" name="body" cols="7"></textarea>

				</form>
				
				<!-- Button -->
				<button class="button full-width button-sliding-icon ripple-effect" type="submit" form="leaveReviewForm2">Leave a Review <i class="icon-material-outline-arrow-right-alt"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Leave a Review Popup / End -->

@endsection

@push('custom-scripts')
<script>
		$(document).ready(function(){
            var leaveReview = $('.leaveReview');
            leaveReview.click(function(){
                var _job = $(this).attr("data-job");
                var job = JSON.parse(_job);

                $('#leaveReviewForm').attr('action', 'review_job/' + job.uuid);
                $('#job_link').attr('href', 'jobs/' + job.slug);
                $('#job_link').text(job.title);
                $('#job_id').val(job.id);
            });

            var leaveReview2 = $('.leaveReview2');
            leaveReview2.click(function(){
                var _job = $(this).attr("data-job");
                var job = JSON.parse(_job);
				

                $('#leaveReviewForm2').attr('action', 'review_freelancer/' + job.profile.uuid);
                $('#job_link2').attr('href', 'jobs/' + job.slug);
                $('#job_link2').text(job.title);
                $('#profile_link').attr('href', 'freelancers/' + job.profile.uuid);
                $('#profile_name').text(job.profile.name);
                $('#user_id').val(job.profile.user_id);
                $('#job_id2').val(job.id);
            });
        });
</script>
    
@endpush