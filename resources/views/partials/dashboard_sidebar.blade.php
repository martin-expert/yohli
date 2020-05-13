    <div class="dashboard-sidebar">
		<div class="dashboard-sidebar-inner" data-simplebar>
			<div class="dashboard-nav-container">

				<!-- Responsive Navigation Trigger -->
				<a href="#" class="dashboard-responsive-nav-trigger">
					<span class="hamburger hamburger--collapse" >
						<span class="hamburger-box">
							<span class="hamburger-inner"></span>
						</span>
					</span>
					<span class="trigger-title">Dashboard Navigation</span>
				</a>
				
				<!-- Navigation -->
				<div class="dashboard-nav">
					<div class="dashboard-nav-inner">

						<ul data-submenu-title="Start">
							<li class="{{ (request()->is('dashboard')) ? 'active' : '' }}">
								<a href="{{ route('dashboard') }}"><i class="icon-material-outline-dashboard"></i> Dashboard</a>
							</li>
							<li class="{{ (request()->is('messages')) ? 'active' : '' }}">
								<a href="{{ route('chats') }}"><i class="icon-material-outline-question-answer"></i> Messages <span class="nav-tag">{{ $chat_notifications }}</span></a>
							</li>
							<li class="{{ (request()->is('bookmarks.index')) ? 'active' : '' }}">
								<a href="{{ route('bookmarks.index') }}"><i class="icon-material-outline-star-border"></i> Bookmarks </a>
							</li>
							<li class="{{ (request()->is('reviews')) ? 'active' : '' }}">
								<a href="{{ route('reviews') }}"><i class="icon-material-outline-rate-review"></i> Reviews</a>
							</li>
						</ul>
						
						<ul data-submenu-title="Organize and Manage">
							<li class="{{ (request()->is('new-jobs') || request()->is('ongoing-jobs') || request()->is('completed-jobs')|| request()->is('bidders') || request()->is('my-bids') || request()->is('invites')) ? 'active-submenu' : '' }}"><a href="#"><i class="icon-material-outline-assignment"></i> Jobs</a>
								<ul>
									@role('hirer')
									<li><a href="{{ route('new-jobs') }}">New Jobs <span class="nav-tag">{{ $new_jobs_count }}</span></a></li>
									{{-- New and Unassigned Jobs just posted by Hirer --}}
									@endrole
									<li>
										<a href="{{ route('ongoing-jobs') }}">Ongoing Jobs 
											<span class="nav-tag">
												{{ $ongoing_jobs_count ?? 0 }}
											</span>
										</a>
									</li>
									{{-- Assigned Jobs being worked on by Freelancer --}}
									<li><a href="{{ route('completed-jobs') }}">Completed Jobs <span class="nav-tag">{{ $completed_jobs_count ?? 0 }}</span></a></li>
									{{-- Past Jobs posted by Hireer or Completed by Freelance --}}
									
									@role('freelancer')
									<li><a href="{{ route('my-bids') }}">My Active Bids <span class="nav-tag">{{ $bids_count ?? 0 }}</span></a></li>
									{{-- Active Jobs bidded on by Freelancer --}}
									@endrole

									{{-- @role('freelancer') --}}
									<li><a href="{{ route('invites') }}">Job Invites <span class="nav-tag">{{ $invites_count ?? 0 }}</span></a></li>
									{{-- @endrole --}}
								</ul>	
							</li>

							<li class="{{ (request()->is('add-funds') || request()->is('withdraw-funds') || request()->is('transactions-history')) ? 'active-submenu' : '' }}">
								<a href="#">
									<i class="icon-line-awesome-money"></i> Finances
								</a>
								<ul>
									<li><a href="{{ route('add-funds') }}">Add Funds </a></li>
									<li><a href="{{ route('withdraw-funds') }}">Withdraw Funds </a></li>
									<li><a href="{{ route('transactions-history') }}">Transaction History</a></li>
								</ul>	
							</li>
						</ul>

						<ul data-submenu-title="Account">
							<li class="{{ (request()->is('cancel')) ? 'active' : '' }}">
								<a href="{{ route('cancel') }}"><i class="icon-line-awesome-euro"></i>Subscription</a>
							</li>
							<li class="{{ (request()->is('settings')) ? 'active' : '' }}">
								<a href="{{ route('settings') }}"><i class="icon-material-outline-settings"></i> Settings</a>
							</li>
							<li>
								<a href="{{ route('logout') }}"  onclick="event.preventDefault();
									document.getElementById('logout-form2').submit();">
									<i class="icon-material-outline-power-settings-new"></i> Logout
									<form id="logout-form2" action="{{ route('logout') }}" method="POST" style="display: none;">
										@csrf
									</form>
								</a>


							</li>
						</ul>
						
					</div>
				</div>
				<!-- Navigation / End -->

			</div>
		</div>
	</div>