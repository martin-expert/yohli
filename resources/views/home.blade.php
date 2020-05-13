@extends('layouts.master')
@section('title', 'Home')

@section('content')

<div class="intro-banner" data-background-image="{{ asset('assets/images/home-background.jpg') }}">
	<div class="container">
		
		<!-- Intro Headline -->
		<div class="row">
			<div class="col-md-12">
				<div class="banner-headline">
					<h3>
						<strong>Hire experts or be hired for any job, any time.</strong>
						<br>
						<span>Thousands of small businesses use <strong class="color">Yohli</strong> to turn their ideas into reality.</span>
					</h3>
				</div>
			</div>
		</div>
		
		<!-- Search Bar -->
		<div class="row">
			<div class="col-md-12">
				<div class="intro-banner-search-form margin-top-95">

					<!-- Search Field -->
					<div class="intro-search-field">
						<label for ="intro-keywords" class="field-title ripple-effect">What job you want?</label>
						<input id="intro-keywords" type="text" placeholder="Job Title or Keywords" class="searchJobs">
					</div>

					<!-- Button -->
					<div class="intro-search-button">
						<button class="button ripple-effect" id="searchBtn">Search</button>
					</div>

					{{-- <form id="search_form" action="/browse-jobs" method="get" style="display: none;">
						<input type="hidden" name="search" id="searchBox">
                        @csrf
                    </form> --}}
				</div>
			</div>
		</div>

		<!-- Stats -->
		<div class="row">
			<div class="col-md-12">
				<ul class="intro-stats margin-top-45 hide-under-992px">
					<li>
						<strong class="counter">{{ $total_jobs->total  ?? 0 }}</strong>
						<span>Jobs Posted</span>
					</li>
					<li>
						<strong class="counter">{{ $total_jobs->completed  ?? 0 }}</strong>
						<span>Jobs Completed</span>
					</li>
					<li>
						<strong class="counter">{{ $total_freelancers  ?? 0 }}</strong>
						<span>Freelancers</span>
					</li>
				</ul>
			</div>
		</div>

	</div>
</div>

<!-- Popular Job Categories -->
<div class="section margin-top-65 margin-bottom-30">
	<div class="container">
		<div class="row">

			<!-- Section Headline -->
			<div class="col-xl-12">
				<div class="section-headline centered margin-top-0 margin-bottom-45">
					<h3>Popular Categories</h3>
				</div>
			</div>
			@foreach($job_categories as $category)
			<div class="col-xl-3 col-md-6">
				<!-- Photo Box -->
				@if (sizeof($category->getMedia('profile')) == 0)
					<a href="/browse-jobs?category={{ $category->id }}" class="photo-box small" data-background-image="{{ asset('assets/images/job-category-02.jpg') }}">
                @else
					<a href="/browse-jobs?category={{ $category->id }}" class="photo-box small" data-background-image="{{ $category->getFirstMediaUrl('cover', 'big') }}">
				@endif	
					<div class="photo-box-content">
						<h3>{{ $category->title  ?? '' }}</h3>
						<span>{{ $category->jobs_count  ?? 0 }}</span>
					</div>
				</a>
			</div>
			@endforeach
		</div>
	</div>
</div>
<!-- Popular Job Categories / End -->
@role('freelancer')
<!-- Features Jobs -->
<div class="section gray margin-top-45 padding-top-65 padding-bottom-75">
	<div class="container">
		<div class="row">
			<div class="col-xl-12">
				
				<!-- Section Headline -->
				<div class="section-headline margin-top-0 margin-bottom-35">
					<h3>Recent Jobs</h3>
					<a href="browse-jobs" class="headline-link">Browse All Jobs</a>
				</div>
				
				<!-- Jobs Container -->
				<div class="tasks-list-container compact-list margin-top-35">
				@foreach($recent_featured_jobs as $job)
					<!-- Task -->
					<a href="jobs/{{ $job->slug }}" class="task-listing">

						<!-- Job Listing Details -->
						<div class="task-listing-details">

							<!-- Details -->
							<div class="task-listing-description">
								<h3 class="task-listing-title">{{ $job->title ?? '' }}</h3>
								<ul class="task-icons">
									<li><i class="icon-material-outline-location-on"></i> {{ $job->country->name ?? ''  }}</li>
									<li><i class="icon-material-outline-access-time"></i> {{ \Carbon\Carbon::parse($job->created)->diffForHumans() }}</li>
								</ul>
								<div class="task-tags margin-top-15">
								@foreach($job->skills as $skill)
									<span>{{ $skill->title  ?? ''}}</span>
								@endforeach
								</div>
							</div>

						</div>

						<div class="task-listing-bid">
							<div class="task-listing-bid-inner">
								<div class="task-offers">
									<strong>${{ $job->min_budget  ?? 0 }}  - ${{ $job->max_budget  ?? 0 }} </strong>
									<span>{{ strtoupper($job->budget_type  ?? '') }}</span>
								</div>
								<span class="button button-sliding-icon ripple-effect">Bid Now <i class="icon-material-outline-arrow-right-alt"></i></span>
							</div>
						</div>
					</a>
					@endforeach
					<!-- Task -->	

				</div>
				<!-- Jobs Container / End -->

			</div>
		</div>
	</div>
</div>
@endrole
<!-- Featured Jobs / End -->
@role('hirer')
<!-- Highest Rated Freelancers -->
<div class="section gray padding-top-65 padding-bottom-70 full-width-carousel-fix">
	<div class="container">
		<div class="row">

			<div class="col-xl-12">
				<!-- Section Headline -->
				<div class="section-headline margin-top-0 margin-bottom-25">
					<h3>Highest Rated Freelancers</h3>
					<a href="browse-freelancers" class="headline-link">Browse All Freelancers</a>
				</div>
			</div>

			<div class="col-xl-12">
				<div class="default-slick-carousel freelancers-container freelancers-grid-layout">
				@foreach($freelancers as $freelancer)
					<!--Freelancer -->
					<div class="freelancer">
						<!-- Overview -->
						<div class="freelancer-overview">
							<div class="freelancer-overview-inner">
								
								
								<!-- Avatar -->
								<div class="freelancer-avatar">
									@if($freelancer->verified == 'yes')
									<div class="verified-badge"></div>
									@endif
									@if (sizeof($freelancer->getMedia('profile')) == 0)
									<a href="freelancers/{{ $freelancer->uuid }}"><img src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt=""></a>
									@else
									<a href="freelancers/{{ $freelancer->uuid }}"><img src="{{ $freelancer->getFirstMediaUrl('profile', 'big') }}" alt=""/></a>
									@endif
								</div>

								<!-- Name -->
								<div class="freelancer-name">
									<h4><a href="freelancers/{{ $freelancer->uuid  ?? '' }}">{{ $freelancer->name ?? '' }}<img class="flag" src="{{ asset('assets/images/flags/'. strtolower($freelancer->country->code.'.svg')) }}" alt="{{ $freelancer->country->name  ?? '' }}" title="{{ $freelancer->country->name ?? '' }}" data-tippy-placement="top"></a></h4>
									<span>{{ str_limit($freelancer->headline , $limit = 35)  }}</span>
								</div>

								<!-- Rating -->
								<div class="freelancer-rating">
									<div class="star-rating" data-rating="{{ $freelancer->rating ?? 0 }}"></div>
								</div>
							</div>
						</div>
						
						<!-- Details -->
						<div class="freelancer-details">
							<div class="freelancer-details-list">
								<ul>
									<li>Country <strong><i class="icon-material-outline-location-on"></i> {{ $freelancer->country->name  ?? '' }}</strong></li>
									<li>Rate <strong>{{ $freelancer->rate  ?? '' }} / hr</strong></li>
									<li>Job Success <strong>{{ $freelancer->completion_rate  ?? '' }}%</strong></li>
								</ul>
							</div>
							<a href="freelancers/{{ $freelancer->uuid }}" class="button button-sliding-icon ripple-effect">View Profile <i class="icon-material-outline-arrow-right-alt"></i></a>
						</div>
					</div>
					<!-- Freelancer / End -->
					@endforeach
				</div>
			</div>

		</div>
	</div>
</div>
<!-- Highest Rated Freelancers / End-->
@endrole
@endsection

@push('custom-scripts')
    <script>
		$(document).ready(function(){
			$('#searchBtn').click(function(){
				let input = $('.searchJobs').val();
				if(input.length == 0){
					Snackbar.show({
						text: "Please type something first",
						pos: 'top-right',
						showAction: false,
						actionText: "Dismiss",
						duration: 3000,
						textColor: '#fff',
						backgroundColor: '#383838'
					});
				} else {
					location.href = '/browse-jobs?search='+input;
				}
			});
		});
	</script>
@endpush