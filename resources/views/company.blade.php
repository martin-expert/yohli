@extends('layouts.master')
@section('title', 'Agency Profile')

@section('content')

<!-- Page Content
================================================== -->
<!-- Titlebar
================================================== -->
<div class="single-page-header"  }}>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="single-page-header-inner">
					<div class="left-side">
						<div class="header-image p-0">
                            @if (sizeof($profile->getMedia('profile')) == 0)
                             <img src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt="">
                            @else
                                <img src="{{ $profile->getFirstMediaUrl('profile', 'big') }}" alt=""/> 
                            @endif	
                        </div>
						<div class="header-details">
							<h3> {{ $profile->name }}</h3>
							<ul>
								<li><div class="star-rating" data-rating="{{ $profile->user->rating ?? 0 }}"></div></li>
								<li><img class="flag" src="{{ asset('assets/images/flags/'. strtolower($profile->country->code.'.svg')) }}" alt="$profile->country->name">{{ $profile->country->name }}</li>
                                @if($profile->verified == 1)
                                <li><div class="verified-badge-with-title">Verified</div></li>
                                @endif
							</ul>
						</div>
					</div>
					<div class="right-side">
						<nav id="breadcrumbs" class="white">
							<ul>
								<li>EMPLOYER PROFILE</li>
							</ul>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Page Content
================================================== -->
<div class="container">
	<div class="row">
		
		<!-- Content -->
		<div class="col-xl-8 col-lg-8 content-right-offset">

			<div class="single-page-section">
                <h3 class="margin-bottom-25">About Company</h3>
                
                {!! $profile->description !!}
            
            </div>
			
			<!-- Boxed List -->
			<div class="boxed-list margin-bottom-60">
				<div class="boxed-list-headline">
					<h3><i class="icon-material-outline-business-center"></i> Open Jobs</h3>
				</div>

				<div class="listings-container compact-list-layout">
                    @foreach($jobs as $job)
					<!-- Job Listing -->
					<a href="../jobs/{{ $job->slug }}" class="job-listing">
    
						<!-- Job Listing Details -->
						<div class="job-listing-details">

							<!-- Details -->
							<div class="job-listing-description">
								<h3 class="job-listing-title">{{ $job->title }}</h3>

								<!-- Job Listing Footer -->
								<div class="job-listing-footer">
									<ul>
										<li><i class="icon-material-outline-location-on"></i>{{ $job->country->name }}</li>
                                        <li><i class="icon-material-outline-monetization-on"></i>
                                            @if($job->budget_type == 'fixed')
                                                Fixed Price
                                            @else
                                                Hourly Rate
                                            @endif
                                            @if ($job->min_budget == $job->max_budget)
                                                {{ '$'.$job->min_budget }}
                                            @else
                                                {{ '$'.$job->min_budget. ' - $' .$job->max_budget }}
                                            @endif
                                        </li>
										<li><i class="icon-material-outline-access-time"></i> {{ $job->created }}</li>
									</ul>
								</div>
							</div>

						</div>

						<!-- Bookmark -->
                        @if($profile->user_id != Auth::id())
                        @role('freelancer')
                        <h3>Bookmark</h3>
                            <span class="bookmark-icon {{ \App\Models\Bookmark::where(['job_id' => $job->id, 'user_id' => Auth::id()])->exists() == 1 ? 'bookmarked' : '' }}"  onclick="bookmark({{ $job->id }})"></span>
                        
                        @endrole
                        @endif
                    </a>
                    @endforeach
				</div>

			</div>
			<!-- Boxed List / End -->

			<!-- Boxed List -->
			<div class="boxed-list margin-bottom-60">
				<div class="boxed-list-headline">
					<h3><i class="icon-material-outline-thumb-up"></i> Reviews</h3>
				</div>
				<ul class="boxed-list-ul">
                    @foreach($reviews as $review)
					<li>
						<div class="boxed-list-item">
							<!-- Content -->
							<div class="item-content">
								<h4><span>{{ $review->user->name}}</span></h4>
								<div class="item-details margin-top-10">
									<div class="star-rating" data-rating="{{ $review->rating }}"></div>
									<div class="detail-item"><i class="icon-material-outline-date-range"></i> {{ $review->created_at }}</div>
								</div>
								<div class="item-description">
									<p>{{ $review->body }} </p>
								</div>
							</div>
						</div>
					</li>
                    @endforeach
                </ul>
            

			</div>
			<!-- Boxed List / End -->

		</div>
		

		<!-- Sidebar -->
		<div class="col-xl-4 col-lg-4">
			<div class="sidebar-container">

				<!-- Location -->
				<!-- <div class="sidebar-widget">
					<h3>Location</h3>
					<div id="single-job-map-container">
						<div id="singleListingMap" data-latitude="52.520007" data-longitude="13.404954" data-map-icon="im im-icon-Hamburger"></div>
						<a href="#" id="streetView">Street View</a>
					</div>
				</div> -->

				<!-- Widget -->
				<!-- <div class="sidebar-widget">
					<h3>Social Profiles</h3>
					<div class="freelancer-socials margin-top-25">
						<ul>
							<li><a href="#" title="Dribbble" data-tippy-placement="top"><i class="icon-brand-dribbble"></i></a></li>
							<li><a href="#" title="Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
							<li><a href="#" title="Behance" data-tippy-placement="top"><i class="icon-brand-behance"></i></a></li>
							<li><a href="#" title="GitHub" data-tippy-placement="top"><i class="icon-brand-github"></i></a></li>
						
						</ul>
					</div>
				</div> -->

				<!-- Sidebar Widget -->
				<div class="sidebar-widget">
                    @if($profile->user_id != Auth::id())
					@role('freelancer')
					<h3>Bookmark</h3>
					<button class="bookmark-button margin-bottom-25">
						<span class="bookmark-icon"></span>
						<span class="bookmark-text">Bookmark</span>
						<span class="bookmarked-text">Bookmarked</span>
					</button>
					
					@endrole
					@endif
					<!-- Copy URL -->
					<h3>Share</h3>
					<div class="copy-url">
						<input id="copy-url" type="text" value="" class="with-border">
						<button class="copy-url-button ripple-effect" data-clipboard-target="#copy-url" title="Copy to Clipboard" data-tippy-placement="top"><i class="icon-material-outline-file-copy"></i></button>
					</div>

					<!-- Share Buttons -->
					<!-- <div class="share-buttons margin-top-25">
						<div class="share-buttons-trigger"><i class="icon-feather-share-2"></i></div>
						<div class="share-buttons-content">
							<span>Interesting? <strong>Share It!</strong></span>
							<ul class="share-buttons-icons">
								<li><a href="#" data-button-color="#3b5998" title="Share on Facebook" data-tippy-placement="top"><i class="icon-brand-facebook-f"></i></a></li>
								<li><a href="#" data-button-color="#1da1f2" title="Share on Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
								<li><a href="#" data-button-color="#dd4b39" title="Share on Google Plus" data-tippy-placement="top"><i class="icon-brand-google-plus-g"></i></a></li>
								<li><a href="#" data-button-color="#0077b5" title="Share on LinkedIn" data-tippy-placement="top"><i class="icon-brand-linkedin-in"></i></a></li>
							</ul>
						</div>
					</div> -->
				</div>

			</div>
		</div>

	</div>
</div>
<script src="{{ asset('js/app.js') }}"></script>

<script>
        function bookmark(id){
            axios.post('../jobs/bookmarks-toggle-api', {
                job_id: id,
            })
            .then(response => {
                Snackbar.show({
                    text: response.data.message,
                    pos: 'bottom-center',
                    showAction: false,
                    actionText: "Dismiss",
                    duration: 3000,
                    textColor: '#fff',
                    backgroundColor: '#383838'
                }); 
            });
        }
    </script>
@endsection
@push('custom-script')

<!-- Snackbar // documentation: https://www.polonel.com/snackbar/ -->
<script>
    
// Snackbar for user status switcher
$('#snackbar-user-status label').click(function() { 
	Snackbar.show({
		text: 'Your status has been changed!',
		pos: 'bottom-center',
		showAction: false,
		actionText: "Dismiss",
		duration: 3000,
		textColor: '#fff',
		backgroundColor: '#383838'
	}); 
}); 

// Snackbar for "place a bid" button
$('#snackbar-place-bid').click(function() { 
	Snackbar.show({
		text: 'Your bid has been placed!',
	}); 
}); 


// Snackbar for copy to clipboard button
$('.copy-url-button').click(function() { 
	Snackbar.show({
		text: 'Copied to clipboard!',
	}); 
}); 


</script>

<!-- Google API & Maps -->
<!-- Geting an API Key: https://developers.google.com/maps/documentation/javascript/get-api-key -->

@endpush