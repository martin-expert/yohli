@extends('layouts.master')
@section('title', 'Blog')
@section('subtitle', 'Featured Posts')
@section('title_bar')
    @include('partials.title_bar')
@endsection

@section('content')
<!-- Recent Blog Posts -->
<div class="section white padding-top-0 padding-bottom-60 full-width-carousel-fix">
	<div class="container">
		<div class="row">
			<div class="col-xl-12">
				<div class="blog-carousel">

					@forelse($featured_posts as $post)
					<a href="blog/{{ $post->slug }}" class="blog-compact-item-container">
						<div class="blog-compact-item">
							<img src="{{ asset('assets/images/blog-04a.jpg') }}" alt="">
							<span class="blog-item-tag">{{ $post->categories[0]->title }}</span>
							<div class="blog-compact-item-content">
								<ul class="blog-post-tags">
									<li>{{ $post->created_at }}</li>
								</ul>
								<h3>{{ $post->title }}</h3>
								<p>{{ $post->body }}</p>
							</div>
						</div>
					</a>
					@empty
					<h3>NO FEATURED POST AVAILABLE</h3>
					@endforelse

				</div>

			</div>
		</div>
	</div>
</div>
<!-- Recent Blog Posts / End -->


<!-- Section -->
<div id="app">
<blog-post></blog-post>
</div>

	<!-- Spacer -->
	<div class="padding-top-40"></div>
	<!-- Spacer -->

</div>
<script src="{{ asset('js/app.js') }}"></script>

<!-- Section / End -->
@endsection
