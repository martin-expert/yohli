@extends('layouts.master')
@section('title', 'Blog')
@section('subtitle', 'Blog Post')
@section('title_bar')
    @include('partials.title_bar')
@endsection

@section('content')

<!-- Post Content -->
<div class="container">
	<div class="row">
		
		<!-- Inner Content -->
		<div class="col-xl-8 col-lg-8">
			<!-- Blog Post -->
			<div class="blog-post single-post">

				<!-- Blog Post Thumbnail -->
				<div class="blog-post-thumbnail">
					<div class="blog-post-thumbnail-inner">
						@foreach($post->categories as $category)
							<span class="blog-item-tag shadow">{{ $category['title'] }}</span>
						@endforeach
						@if(count($post->media) == 0)							
							<img src="{{ asset("assets/images/blog-04.jpg") }}" alt="">
						@endif
						@if(count($post->media) > 1)
							{{ $post->getFirstMedia() }}
						@endif
					</div>
				</div>

				<!-- Blog Post Content -->
				<div class="blog-post-content">
					<h3 class="margin-bottom-10">{{ $post->title }}</h3>

					<div class="blog-post-info-list margin-bottom-20">
						<a href="#" class="blog-post-info shadow">{{ $post->created_at }}</a>
						{{-- <a href="#"  class="blog-post-info">5 Comments</a> --}}
					</div>

					<div>{!! $post->body !!}</div>
					<p class="mt-3">Tags</p>
					@foreach($post->tags as $tag)
						<span class="blog-post-info shadow ">{{ $tag['title'] }}</span>
					@endforeach

					<!-- Share Buttons -->
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
					</div>
				</div>

			</div>
			<!-- Blog Post Content / End -->
			
			{{-- <!-- Blog Nav -->
			<ul id="posts-nav" class="margin-top-0 margin-bottom-40">
				<li class="next-post">
					<a href="#">
						<span>Next Post</span>
						<strong>16 Ridiculously Easy Ways to Find & Keep a Remote Job</strong>
					</a>
				</li>
				<li class="prev-post">
					<a href="#">
						<span>Previous Post</span>
						<strong>11 Tips to Help You Get New Clients Through Cold Calling</strong>
					</a>
				</li>
			</ul> --}}
			
			{{-- <!-- Related Posts -->
			<div class="row">
				
				<!-- Headline -->
				<div class="col-xl-12">
					<h3 class="margin-top-40 margin-bottom-35">Related Posts</h3>
				</div>

				<!-- Blog Post Item -->
				<div class="col-xl-6">
					<a href="#" class="blog-compact-item-container">
						<div class="blog-compact-item">
							<img src="{{ asset("assets/images/blog-02a.jpg") }}" alt="">
							<span class="blog-item-tag">Recruiting</span>
							<div class="blog-compact-item-content">
								<ul class="blog-post-tags">
									<li>29 June 2019</li>
								</ul>
								<h3>How to "Woo" a Recruiter and Land Your Dream Job</h3>
								<p>Appropriately empower dynamic leadership skills after business portals. Globally myocardinate interactive.</p>
							</div>
						</div>
					</a>
				</div>
				<!-- Blog post Item / End -->

				<!-- Blog Post Item -->
				<div class="col-xl-6">
					<a href="#" class="blog-compact-item-container">
						<div class="blog-compact-item">
							<img src="{{ asset("assets/images/blog-03a.jpg") }}" alt="">
							<span class="blog-item-tag">Marketing</span>
							<div class="blog-compact-item-content">
								<ul class="blog-post-tags">
									<li>10 June 2019</li>
								</ul>
								<h3>11 Tips to Help You Get New Clients Through Cold Calling</h3>
								<p>Compellingly embrace empowered e-business after user friendly intellectual capital. Interactively front-end.</p>
							</div>
						</div>
					</a>
				</div>
				<!-- Blog post Item / End -->
			</div>
			<!-- Related Posts / End -->
				

			<!-- Comments -->
			<div class="row">
				<div class="col-xl-12">
					<section class="comments">
						<h3 class="margin-top-45 margin-bottom-0">Comments <span class="comments-amount">(5)</span></h3>

						<ul>
							<li>
								<div class="avatar"><img src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt=""></div>
								<div class="comment-content"><div class="arrow-comment"></div>
									<div class="comment-by">Kathy Brown<span class="date">12th, June 2019</span>
										<a href="#" class="reply"><i class="fa fa-reply"></i> Reply</a>
									</div>
									<p>Morbi velit eros, sagittis in facilisis non, rhoncus et erat. Nam posuere tristique sem, eu ultricies tortor imperdiet vitae. Curabitur lacinia neque non metus</p>
								</div>

								<ul>
									<li>
										<div class="avatar"><img src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt=""></div>
										<div class="comment-content"><div class="arrow-comment"></div>
											<div class="comment-by">Tom Smith<span class="date">12th, June 2019</span>
												<a href="#" class="reply"><i class="fa fa-reply"></i> Reply</a>
											</div>
											<p>Rrhoncus et erat. Nam posuere tristique sem, eu ultricies tortor imperdiet vitae. Curabitur lacinia neque.</p>
										</div>
									</li>
									<li>
										<div class="avatar"><img src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt=""></div>
										<div class="comment-content"><div class="arrow-comment"></div>
											<div class="comment-by">Kathy Brown<span class="date">12th, June 2019</span>
												<a href="#" class="reply"><i class="fa fa-reply"></i> Reply</a>
											</div>
											<p>Nam posuere tristique sem, eu ultricies tortor.</p>
										</div>

										<ul>
											<li>
												<div class="avatar"><img src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt=""></div>
												<div class="comment-content"><div class="arrow-comment"></div>
													<div class="comment-by">John Doe<span class="date">12th, June 2019</span>
														<a href="#" class="reply"><i class="fa fa-reply"></i> Reply</a>
													</div>
													<p>Great template!</p>
												</div>
											</li>
										</ul>

									</li>
								</ul>

							</li>

							<li>
								<div class="avatar"><img src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt=""> </div>
								<div class="comment-content"><div class="arrow-comment"></div>
									<div class="comment-by">John Doe<span class="date">15th, May 2015</span>
										<a href="#" class="reply"><i class="fa fa-reply"></i> Reply</a>
									</div>
									<p>Commodo est luctus eget. Proin in nunc laoreet justo volutpat blandit enim. Sem felis, ullamcorper vel aliquam non, varius eget justo. Duis quis nunc tellus sollicitudin mauris.</p>
								</div>

							</li>
						 </ul>

					</section>
				</div>
			</div>
			<!-- Comments / End -->


			<!-- Leava a Comment -->
			<div class="row">
				<div class="col-xl-12">
					
					<h3 class="margin-top-35 margin-bottom-30">Add Comment</h3>

					<!-- Form -->
					<form method="post" id="add-comment">

						<div class="row">
							<div class="col-xl-6">
								<div class="input-with-icon-left no-border">
									<i class="icon-material-outline-account-circle"></i>
									<input type="text" class="input-text" name="commentname" id="namecomment" placeholder="Name" required/>
								</div>
							</div>
							<div class="col-xl-6">
								<div class="input-with-icon-left no-border">
									<i class="icon-material-baseline-mail-outline"></i>
									<input type="text" class="input-text" name="emailaddress" id="emailaddress" placeholder="Email Address" required/>
								</div>
							</div>
						</div>

						<textarea  name="comment-content" cols="30" rows="5" placeholder="Comment"></textarea>
					</form>
					
					<!-- Button -->
					<button class="button button-sliding-icon ripple-effect margin-bottom-40" type="submit" form="add-comment">Add Comment <i class="icon-material-outline-arrow-right-alt"></i></button>
					
				</div>
			</div>
			<!-- Leava a Comment / End --> --}}

		</div>
		<!-- Inner Content / End -->


		<div class="col-xl-4 col-lg-4 content-left-offset">
			<div class="sidebar-container">

				<!-- Widget -->
					<div class="sidebar-widget">

						<h3>Trending Posts</h3>
						<ul class="widget-tabs">

							<!-- Post #1 -->
							@forelse ($trending_posts as $post)
								<li>
									<a href="{{ route('blog.show', $post->slug) }}" class="widget-content active">
										<img src="{{ asset('assets/images/blog-02a.jpg') }}" alt="">
										<div class="widget-text">
											<h5>{{ $post->title }}</h5>
											<span>{{ $post->created_at }}</span>
										</div>
									</a>
								</li>	
							@empty
								<h3>NO TRENDING POSTS AT THE MOMENT</h3>
							@endforelse

						</ul>

					</div>
				<!-- Widget / End-->

				<!-- Widget -->
				<div class="sidebar-widget">
					<h3>Tags</h3>
					<div class="task-tags text-lowercase">
						@foreach ($tags as $tag)
							<a href="/blog?tag={{ $tag->slug }}"><span>{{ $tag->title }}</span></a>
						@endforeach
					</div>
				</div>

			</div>
		</div>

	</div>
</div>

<!-- Spacer -->
<div class="padding-top-40"></div>
<!-- Spacer -->


@endsection
