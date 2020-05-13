@extends('layouts.master')
@section('title',  $freelancer->name ?? "")

@section('content')
<!-- Titlebar
================================================== -->
<div class="single-page-header freelancer-header" data-background-image="{{ asset('assets/images/single-freelancer.jpg') }}">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="single-page-header-inner">
					<div class="left-side">
						<div class="header-image  freelancer-avatar">
                            @if (sizeof($freelancer->getMedia('profile')) == 0)
                             <img src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt="">
                            @else
                                <img src="{{ $freelancer->getFirstMediaUrl('profile', 'big') }}" alt=""/> 
                            @endif
                        </div>
						<div class="header-details">
							<h3>{{ $freelancer->name ?? "" }} <span>{{ $freelancer->headline ?? "No Headline" }}</span></h3>
							<ul>
								<li><div class="star-rating" data-rating="{{ $freelancer->rating ?? 0 }}"></div></li>
                                <li><img class="flag" src="{{ asset('assets/images/flags/'. strtolower($freelancer->country->code.'.svg')) }}" alt="{{ $freelancer->country->name }}"> {{ $freelancer->country->name }}</li>
                                @if($freelancer->verified == 1)
                                <li><div class="verified-badge-with-title">Verified</div></li>
                                @endif
							</ul>
						</div>
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
                
                <!-- Page Content -->
                <div class="single-page-section">
                    <h3 class="margin-bottom-25">About Me</h3>
                    <div>
                        {!! $freelancer->description !!}
                    </div>
                </div>
    
                <!-- Boxed List -->
               <div class="boxed-list margin-bottom-60">
                    <div class="boxed-list-headline">
                        <h3><i class="icon-material-outline-thumb-up"></i> Work History and Feedback</h3>
                    </div>
                    <ul class="boxed-list-ul">
                    @foreach($jobs as $job)
                        <li>
                            <div class="boxed-list-item">
                                <!-- Content -->
                                <div class="item-content">
                                    <h4>{{ $job->title }}</h4>
                                    <div class="item-details margin-top-10">
                                        <div class="star-rating" data-rating="{{ $job->profile->rating ?? 0 }}"></div>
                                        <div class="detail-item"><i class="icon-material-outline-date-range"></i> {{ \Carbon\Carbon::parse($job->created)->toDateString() }}</div>
                                    </div>
                                    <div class="item-details margin-top-10">
                                    <div class="detail-item"><i class="icon-material-outline-monetization-on"></i>On budget:  {{ strtoupper($job->onbudget) }}</div>
                                    <div class="detail-item"><i class="icon-material-outline-access-time"></i>On Time:  {{ strtoupper($job->ontime) }}</div>
                                    </div>
                                    <div class="item-description">
                                        <p>{!! str_limit($job->description, $limit = 250, $end = '...') !!} </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                    </ul>
    
                    <!-- Pagination -->
                    <div class="clearfix"></div>
                    {{ $jobs->links() }}
                    <div class="clearfix"></div>
                    <!-- Pagination / End -->
    
                </div> 
                <!-- Boxed List / End -->
                
    
            </div>
            
    
            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-4">
                <div class="sidebar-container">
                    
                    <!-- Profile Overview -->
                    <div class="profile-overview">
                        <div class="overview-item"><strong>${{ $freelancer->rate }}</strong><span>Hourly Rate</span></div>
                        <div class="overview-item"><strong>{{ sizeof($freelancer->jobs_completion) }}</strong><span>Jobs Done</span></div>
                        {{-- <div class="overview-item"><strong>22</strong><span>Rehired</span></div> --}}
                    </div>
    
                    <!-- Button -->
                    @role('hirer')
                    <a href="#small-dialog" class="apply-now-button popup-with-zoom-anim margin-bottom-50 recruit" data-freelancer="{{ $freelancer }}">Recruit Me <i class="icon-material-outline-arrow-right-alt"></i></a>
                    @endrole

                    <!-- Freelancer Indicators -->
                    <div class="sidebar-widget">
                        <div class="freelancer-indicators">
    
                            <!-- Indicator -->
                            <div class="indicator">
                                <strong>{{ $freelancer->completion_rate }}%</strong>
                                <div class="indicator-bar" data-indicator-percentage="{{ $freelancer->completion_rate }}"><span></span></div>
                                <span>Job Success</span>
                            </div>
    
                            {{-- <!-- Indicator -->
                            <div class="indicator">
                                <strong>100%</strong>
                                <div class="indicator-bar" data-indicator-percentage="100"><span></span></div>
                                <span>Recommendation</span>
                            </div> --}}
                            
                             <!-- Indicator -->
                            <div class="indicator">
                                <strong>{{ $freelancer->completion_time_rate }}%</strong>
                                <div class="indicator-bar" data-indicator-percentage="{{ $freelancer->completion_time_rate }}"><span></span></div>
                                <span>On Time</span>
                            </div>	
                                                
                            <!-- Indicator -->
                            <div class="indicator">
                                <strong>{{ $freelancer->completion_budget_rate }}%</strong>
                                <div class="indicator-bar" data-indicator-percentage="{{ $freelancer->completion_budget_rate }}"><span></span></div>
                                <span>On Budget</span>
                            </div> 
                        </div>
                    </div>
                    
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
    
                    <!-- Widget -->
                    <div class="sidebar-widget">
                        <h3>Skills</h3>
                        <div class="task-tags">
                            @forelse ($freelancer->skills as $skill)
                            <span>{{ $skill->title }}</span>
                            @empty
                               No Skills 
                            @endforelse
                        </div>
                    </div>
    
                    <!-- Widget -->
                    {{-- <div class="sidebar-widget">
                        <h3>Attachments</h3>
                        <div class="attachments-container">
                            <a href="#" class="attachment-box ripple-effect"><span>Cover Letter</span><i>PDF</i></a>
                            <a href="#" class="attachment-box ripple-effect"><span>Contract</span><i>DOCX</i></a>
                        </div>
                    </div> --}}
    
                    <!-- Sidebar Widget -->
                    <div class="sidebar-widget">
                        {{-- <h3>Bookmark or Share</h3> --}}
                        @role('hirer')
                        @if(Auth::id() != $freelancer->user_id)
                        <h3>Bookmark</h3>
    
                        <!-- Bookmark Button -->
                        <button class="bookmark-button margin-bottom-25 {{ $isBookmakedByUser == 1 ? 'bookmarked' : '' }}" onclick="bookmark({{ $freelancer->id }})">
                            <span class="bookmark-icon"></span>
                            <span class="bookmark-text">Bookmark</span>
                            <span class="bookmarked-text">Bookmarked</span>
                        </button>
                        @endif
                        @endrole
    
                        <!-- Copy URL -->
                        <h3>Share</h3>
                        <div class="copy-url">
                            <input id="copy-url" type="text" value="" class="with-border">
                            <button class="copy-url-button ripple-effect" data-clipboard-target="#copy-url" title="Copy to Clipboard" data-tippy-placement="top"><i class="icon-material-outline-file-copy"></i></button>
                        </div> 
    
                        {{-- <!-- Share Buttons -->
                        <div class="share-buttons margin-top-25">
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
                        </div> --}}
                    </div>
    
                </div>
            </div>
    
    </div>
</div>

<!-- Recruit Me Popup
================================================== -->
<div id="small-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab">Recruit Me</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>Discuss your project with {{ $freelancer->name }}</h3>
				</div>
					
				<!-- Form -->
				<form method="post" action="{{ route('new-invite') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="submit-field">
                        <select name="job" class="selectpicker" data-live-search="true" title="Choose Job" required>
                            @foreach ($hirerJobs as $job)
                            <option value="{{ $job->id }}">{{ $job->title }}</option>
                            @endforeach
                        </select>
                    </div>

					<textarea name="message" cols="10" placeholder="Message" class="with-border" required></textarea>

					<div class="uploadButton margin-top-25">
                        <input class="uploadButton-input" type="file" accept="image/*, application/pdf, application/docx, application/doc, application/csv" id="upload" name="documents[]" multiple/>
						<label class="uploadButton-button ripple-effect" for="upload">Add Attachments</label>
						<span class="uploadButton-file-name">Allowed file types: zip, pdf, png, jpg <br> Max. files size: 2 MB.</span>
					</div>

                    <input type="hidden" value="{{ $freelancer->id }}" name="profile"/>
				<button class="button margin-top-35 full-width button-sliding-icon ripple-effect" type="submit">Recruit <i class="icon-material-outline-arrow-right-alt"></i></button>

				</form>
				                

			</div>

		</div>
	</div>
</div>
<!-- Recruit Me Popup / End -->
<script src="{{ asset('js/app.js') }}"></script>

<script>
        function bookmark(id){
            axios.post('bookmarks-toggle-api', {
                profile_id: id,
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

@push('custom-scripts')
    <script>
        $(document).ready(function(){
            $('.recruit').click(function(){
                var _freelancer = $(this).attr("data-freelancer");
                var freelancer = JSON.parse(_freelancer);
                
                // $('#bidPrice').text('$'+ThousandSeparator2(bid.rate));
                // $('#acceptBidText').text('Accept Offer From '+bid.profile.name);
                // $('#aProfileId').val(bid.profile.id);
                // $('#acceptBidForm').attr('action', 'accept_bid/'+bid.uuid);
            });
        });
    </script>
@endpush
