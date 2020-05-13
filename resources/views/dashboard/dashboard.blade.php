@extends('layouts.dashboard_master')
@section('title', 'Dashboard')

@section('content')
<div class="row p-3">
    <!-- Dashboard Content
	================================================== -->
	
			@role('freelancer')
			<!-- Fun Facts Container -->
			<div class="fun-facts-container">
				<div class="fun-fact" data-fun-fact-color="#36bd78">
					<div class="fun-fact-text">
						<span>Completed Jobs</span>
						<h4>{{ $completed_jobs }}</h4>
					</div>
					<div class="fun-fact-icon"><i class="icon-material-outline-gavel"></i></div>
				</div>
				<div class="fun-fact" data-fun-fact-color="#36bd78">
					<div class="fun-fact-text">
						<span>Open Bids</span>
						<h4>{{ $bids_count }}</h4>
					</div>
					<div class="fun-fact-icon"><i class="icon-material-outline-gavel"></i></div>
				</div>
				<div class="fun-fact" data-fun-fact-color="#b81b7f">
					<div class="fun-fact-text">
						<span>Ongoing Jobs</span>
						<h4>{{ $ongoing_jobs_count }}</h4>
					</div>
					<div class="fun-fact-icon"><i class="icon-material-outline-business-center"></i></div>
				</div>
				<div class="fun-fact" data-fun-fact-color="#efa80f">
					<div class="fun-fact-text">
						<span>Rating</span>
						<h4>{{ $profile->rating }}</h4>
					</div>
					<div class="fun-fact-icon"><i class="icon-material-outline-rate-review"></i></div>
				</div>

			
			
			<!-- Row -->
			<div class="xl:flex xl:flex-row xl:w-full">

				<div class="xl:w-8/12 lg:mr-1">
					<!-- Dashboard Box -->
					<div class="dashboard-box main-box-in-row">
						<div class="headline">
							<h3><i class="icon-feather-bar-chart-2"></i> Your Profile Views</h3>
							<div class="sort-by">
							 <h4>Total Views: {{ $profile_views->total }}</h4> <h4>Monthly Views: {{ $monthly_views }} </h4>
							</div>
						</div>
						<div class="content">
							<!-- Chart -->
							<div class="chart">
								<canvas id="chart" width="100" height="45"></canvas>
							</div>
						</div>
					</div>
					<!-- Dashboard Box / End -->
				</div>
				<div class="xl:w-4/12 lg:ml-1 h-64">

					<!-- Dashboard Box -->
					<!-- If you want adjust height of two boxes 
						 add to the lower box 'main-box-in-row' 
						 and 'child-box-in-row' to the higher box -->
                         <div class="dashboard-box">
						<div class="headline">
							<h3><i class="icon-material-outline-assignment"></i> Jobs</h3>
						</div>
						<div class="content">
							<ul class="dashboard-box-list">
							@foreach($jobs as $job)
								<li>
									<div class="invoice-list-item">
									<strong>{{ $job->title }}</strong>
										<ul>
											<li>
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
											<li>Date: {{ $job->created }}</li>
											<li><span class="@if($job->status == 'completed') paid @else unpaid @endif">{{ $job->status }}</span></li>
										</ul>
									</div>
									<!-- Buttons -->
									<div class="buttons-to-right">
										<a href="../jobs/{{ $job->slug }}" class="button">View Job</a>
									</div>
								</li>
								@endforeach
							</ul>
						</div>
					</div>
					<!-- Dashboard Box / End -->
				</div>
			</div>
		
			<!-- Row / End -->
			@endrole('freelancer')

			@role('hirer')
			<!-- Fun Facts Container -->
			<div class="fun-facts-container">
				<div class="fun-fact" data-fun-fact-color="#36bd78">
					<div class="fun-fact-text">
						<span>Pending Bids</span>
						<h4>{{ $pending_bids }}</h4>
					</div>
					<div class="fun-fact-icon"><i class="icon-material-outline-gavel"></i></div>
				</div>
				<div class="fun-fact" data-fun-fact-color="#b81b7f">
					<div class="fun-fact-text">
						<span>Ongoing Jobs</span>
					<h4>{{ $count_ongoing_jobs }}</h4>
					</div>
					<div class="fun-fact-icon"><i class="icon-material-outline-business-center"></i></div>
				</div>
				<div class="fun-fact" data-fun-fact-color="#efa80f">
					<div class="fun-fact-text">
						<span>Completed Jobs</span>
						<h4>{{ $completed_jobs }}</h4>
					</div>
					<div class="fun-fact-icon"><i class="icon-feather-briefcase"></i></div>
				</div>

				<!-- Last one has to be hidden below 1600px, sorry :( -->
				<div class="fun-fact" data-fun-fact-color="#2a41e6">
					<div class="fun-fact-text">
						<span>This Month Views</span>
						<h4>{{ $monthly_views }}</h4>
					</div>
					<div class="fun-fact-icon"><i class="icon-feather-trending-up"></i></div>
				</div>
			</div>
			
			<!-- Row -->
			<div class="flex flex-row w-full flex-wrap justify-between ">
				
				<div class="w-full">
					<!-- Dashboard Box -->
					<div class="dashboard-box main-box-in-row">
						<div class="headline">
							<h3><i class="icon-feather-bar-chart-2"></i> Your Profile Views</h3>
							<div class="sort-by">
							<h4>Total Views: {{ $profile_views->total }}</h4> <h4>Monthly Views: {{ $monthly_views }} </h4>
							</div>
						</div>
						<div class="content">
							<!-- Chart -->
							<div class="chart">
								<canvas id="chart" width="100" height="45"></canvas>
							</div>
						</div>
					</div>
					<!-- Dashboard Box / End -->
				</div>
				<div class="w-full">

					<!-- Dashboard Box -->
					<!-- If you want adjust height of two boxes 
						 add to the lower box 'main-box-in-row' 
						 and 'child-box-in-row' to the higher box -->
                        <div class="dashboard-box">
							<div class="headline">
								<h3><i class="icon-material-outline-assignment"></i>Ongoing Jobs</h3>
							</div>
							<div class="content">
								<ul class="dashboard-box-list">
									@foreach($jobs as $job)
									<li>
										<div class="invoice-list-item">
										<strong>{{ $job->title }}</strong>
											<ul>
												<li>
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
												<li>Date: {{ $job->created }}</li>
												<li><span class="@if($job->status == 'completed') paid @else unpaid @endif">{{ $job->status }}</span></li>
											</ul>
										</div>
										<!-- Buttons -->
										<div class="buttons-to-right">
											<a href="../jobs/{{ $job->slug }}" class="button">View Job</a>
										</div>
									</li>
									@endforeach
								</ul>
							</div>
						</div>
					<!-- Dashboard Box / End -->
				</div>
			</div>
			<!-- Row / End -->
			@endrole('hirer')
</div>
@endsection

@push('custom-scripts')
<!-- Chart.js // documentation: http://www.chartjs.org/docs/latest/ -->
<script src="{{ asset('assets/js/chart.min.js') }}"></script>

<script>
	Chart.defaults.global.defaultFontFamily = "Nunito";
	Chart.defaults.global.defaultFontColor = '#888';
	Chart.defaults.global.defaultFontSize = '14';

	var views = <?= json_encode($profile_views) ?>;
	console.log(views);
	
	var ctx = document.getElementById('chart').getContext('2d');

	var chart = new Chart(ctx, {
		type: 'line',

		// The data for our dataset
		data: {
			labels: ["January", "February", "March", "April", "May", "June", "January", "February", "March", "April", "May", "June"],
			// Information about the dataset
	   		datasets: [{
				label: "Views",
				backgroundColor: 'rgba(42,65,232,0.08)',
				borderColor: '#2a41e8',
				borderWidth: "3",
				data: [views.jan, views.feb, views.mar, views.apr, views.may, views.jun, views.jul, views.aug, views.sep, views.oct, views.nov, views.dec],
				pointRadius: 5,
				pointHoverRadius:5,
				pointHitRadius: 10,
				pointBackgroundColor: "#fff",
				pointHoverBackgroundColor: "#fff",
				pointBorderWidth: "2",
			}]
		},

		// Configuration options
		options: {

		    layout: {
		      padding: 10,
		  	},

			legend: { display: false },
			title:  { display: false },

			scales: {
				yAxes: [{
					scaleLabel: {
						display: false
					},
					gridLines: {
						 borderDash: [6, 10],
						 color: "#d8d8d8",
						 lineWidth: 1,
	            	},
				}],
				xAxes: [{
					scaleLabel: { display: false },  
					gridLines:  { display: false },
				}],
			},

		    tooltips: {
		      backgroundColor: '#333',
		      titleFontSize: 13,
		      titleFontColor: '#fff',
		      bodyFontColor: '#fff',
		      bodyFontSize: 13,
		      displayColors: false,
		      xPadding: 10,
		      yPadding: 10,
		      intersect: false
		    }
		},


});
</script>

@endpush