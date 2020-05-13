<div id="footer">
	
	<!-- Footer Top Section -->
	<div class="footer-top-section">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">

					<!-- Footer Rows Container -->
					<div class="footer-rows-container">
						
						<!-- Left Side -->
						<div class="footer-rows-left">
							<div class="footer-row">
								<div class="footer-row-inner footer-logo">
									<img src="{{ asset('assets/images/logo2.jpeg') }}" alt="">
								</div>
							</div>
						</div>
						
						<!-- Right Side -->
						 <div class="footer-rows-right">

							<!-- Social Icons -->
							<div class="footer-row">
								<div class="footer-row-inner">
									<ul class="footer-social-links">
										<li>
											<a href="#" title="Facebook" data-tippy-placement="bottom" data-tippy-theme="light">
												<i class="icon-brand-facebook-f"></i>
											</a>
										</li>
										<li>
											<a href="#" title="Twitter" data-tippy-placement="bottom" data-tippy-theme="light">
												<i class="icon-brand-twitter"></i>
											</a>
										</li>
										<li>
											<a href="#" title="Google Plus" data-tippy-placement="bottom" data-tippy-theme="light">
												<i class="icon-brand-google-plus-g"></i>
											</a>
										</li>
										<li>
											<a href="#" title="LinkedIn" data-tippy-placement="bottom" data-tippy-theme="light">
												<i class="icon-brand-linkedin-in"></i>
											</a>
										</li>
									</ul>
									<div class="clearfix"></div>
								</div>
							</div>
							
						</div> 

					</div>
					<!-- Footer Rows Container / End -->
				</div>

				
					<!-- Footer Middle Section / End -->
			</div>
		</div>
	</div>
	<!-- Footer Top Section / End -->	

		<!-- Footer Middle Section -->
		<div class="footer-middle-section">
						<div class="container">
							<div class="row">

								<!-- Links -->
								<div class="col-xl-2 col-lg-2 col-md-3">
									<div class="footer-links">
										<h3>For Freelancers</h3>
										<ul>
											<li><a href="../browse-jobs"><span>Browse Jobs</span></a></li>
											<li><a href="../bookmarks"><span>My Bookmarks</span></a></li>
										</ul>
									</div>
								</div>

								<!-- Links -->
								<div class="col-xl-2 col-lg-2 col-md-3">
									<div class="footer-links">
										<h3>For Hirers</h3>
										<ul>
											<li><a href="../browse-freelancers"><span>Browse Freelnacers</span></a></li>
											<li><a href=
												@auth
												../post-job
												@endauth
												@guest
												../login
												@endguest
											><span>Post a Job</span></a></li>
										</ul>
									</div>
								</div>

								<!-- Links -->
								<div class="col-xl-2 col-lg-2 col-md-3">
									<div class="footer-links">
										<h3>Helpful Links</h3>
										<ul>
											<li><a href="#"><span>Contact us</span></a></li>
											<li><a href="../copyright"><span>Copyright Policy</span></a></li>
											<li><a href="../terms"><span>Terms of Use</span></a></li>
										</ul>
									</div>
								</div>

								<!-- Links -->
								<div class="col-xl-2 col-lg-2 col-md-3">
									<div class="footer-links">
										<h3>Account</h3>
										<ul>
											<li><a href=
												@auth
													../dashboard
												@endauth
												@guest
													../login
												@endguest
												><span>
													@auth
														Dashboard
													@endauth
													@guest
														Login
													@endguest
												</span></a></li>
											<li><a href=
												@auth
													../settings
												@endauth
												@guest
													../login
												@endguest
											><span>
												@auth
													My Account
												@endauth
												@guest
													Register
												@endguest</span></a></li>
										</ul>
									</div>
								</div>

								<!-- Newsletter -->
								<div class="col-xl-4 col-lg-4 col-md-12">
									<h3><i class="icon-feather-mail"></i> Contact </h3>
									<p>	
										<br>Email: info@yohli.com
										<br>Address: 141 Golf Terrace Dr 
								    </p>
								</div>
							</div>
						</div>
					</div>
	
	<!-- Footer Copyrights -->
	<div class="footer-bottom-section">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					© <?=date('Y') ?> <strong>Yohli</strong>. All Rights Reserved.
				</div>
			</div>
		</div>
	</div>
	<!-- Footer Copyrights / End -->

</div>