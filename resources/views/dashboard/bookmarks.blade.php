@extends('layouts.dashboard_master')
@section('title', 'Bookmarks')

@section('content')

<!-- Row -->
<div class="row" id="app">

	@role('freelancer')
	<!-- Dashboard Box -->
	<div class="col-xl-12">
		<div class="dashboard-box margin-top-0">

			<!-- Headline -->
			<div class="headline">
				<h3><i class="icon-material-outline-business-center"></i> Bookmarked Jobs</h3>
			</div>

			<div class="content">
				<ul class="dashboard-box-list">
					@forelse ($bookmarks as $bookmark)
					<li>
						<!-- Overview -->
						<div class="freelancer-overview">
							<div class="freelancer-overview-inner">
								<h4 class="font-weight-bold">
									<a href="{{ route('jobs.show', $bookmark->job->slug) }}">{{ $bookmark->job->title ?? "" }} 
									</a>
								</h4>
							</div>
						</div>

						<!-- Buttons -->
						<div class="buttons-to-right">
							<a href="{{ route('bookmarks.destroy', $bookmark->id) }}" onclick="event.preventDefault();
									if(confirm('Are you sure you want to delete?')){document.getElementById('remove-form').submit();}" 
									class="button red ripple-effect ico" title="Remove" data-tippy-placement="left">
								<i class="icon-feather-trash-2"></i>
							</a>

							<form id="remove-form" method="post" action="{{ route("bookmarks.destroy", $bookmark->id) }}" style="display: none;">
								@method("delete")
                            	@csrf
                            </form>
						</div>
					</li>
					@empty
						<p class="text-center text-muted py-5">Bookmarks Empty</p>
					@endforelse
				</ul>
			</div>
		</div>
	</div>
	@endrole

	@role('hirer')
	<!-- Dashboard Box -->
	<div class="col-xl-12">
		<div class="dashboard-box">

			<!-- Headline -->
			<div class="headline">
				<h3><i class="icon-material-outline-face"></i> Bookmarked Freelancers</h3>
			</div>

			<div class="content">
				<ul class="dashboard-box-list">
					@forelse ($bookmarks as $bookmark)
					<li>
						<!-- Overview -->
						<div class="freelancer-overview">
							<div class="freelancer-overview-inner">
								<!-- Avatar -->
								<div class="freelancer-avatar">
									@if($bookmark->freelancer->verified == 1)
									<div class="verified-badge"></div>
									@endif
									<a href="{{ route('freelancers.show', $bookmark->freelancer->id) }}">
									@if (sizeof($bookmark->freelancer->getMedia('profile')) == 0)
										<img src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt="">
									@else
										<img src="{{ $bookmark->freelancer->getFirstMediaUrl('profile', 'big') }}" alt=""/>
									@endif
									</a>
								</div>
								<!-- Name -->
								<div class="freelancer-name">
									<h4><a href="{{ route('freelancers.show', $bookmark->freelancer->id) }}">{{ $bookmark->freelancer->name ?? "" }} <img class="flag" src="{{ asset('assets/images/flags/'. strtolower($bookmark->freelancer->country->code) .'.svg') }}" alt="" title="{{ $bookmark->freelancer->country->name }}" data-tippy-placement="top"></a></h4>
									<span>{{ $bookmark->freelancer->headline ?? "" }}</span>
									<!-- Rating -->
									<div class="freelancer-rating">
										<div class="star-rating" data-rating="4.2"></div>
									</div>
								</div>
							</div>
						</div>

						<!-- Buttons -->
						<!-- <div class="buttons-to-right">
							<a href="{{ route('bookmarks.destroy', $bookmark->id) }}" onclick="event.preventDefault();
									document.getElementById('remove-form').submit();" 
									class="button red ripple-effect ico" title="Remove" data-tippy-placement="left">
								<i class="icon-feather-trash-2"></i>
							</a>

							<form id="remove-form" method="post" action="{{ route("bookmarks.destroy", $bookmark->id) }}" style="display: none;">
								@method("delete")
                            	@csrf
                            </form>
						</div> -->
					</li>
					@empty
						<p class="text-center text-muted py-5">Bookmarks Empty</p>
					@endforelse
				</ul>
			</div>
		</div>
	</div>
	@endrole

</div>
<!-- Row / End -->


@endsection