@extends('layouts.master')
@section('title', 'Post a job')

@section('content')
@role('freelancer')
<div class="row mb-5 pj-page margin-top-100 margin-bottom-100">
	<div class="col-6 offset-3">
		<div class="notification error">
			<p class="text-center" ><i class="icon-line-awesome-exclamation-circle" style="font-size: 20px;"></i>
				Sorry, you are NOT authorized to post a job, Switch to hirer to continue.
			</p>
		</div>
	</div>
</div>
@endrole
@role('hirer')
<div class="row mb-5 pj-page margin-top-100 margin-bottom-100">
		<div class="col-sm-12 col-md-6 offset-md-3">
			@include('partials.alert')
		</div>

	<!-- Dashboard Box -->
	<div class="col-sm-12 col-md-6 offset-md-3 z-200 relative">
		<div class="dashboard-box margin-top-0">

			<!-- Headline -->
			<div class="headline">
				<h3><i class="icon-feather-folder-plus"></i> Tell us what you need done</h3>
			</div>

			<div class="content with-padding padding-bottom-10">
				<form action="{{ route('jobs.store') }}" method="POST" id="post_job_form" enctype="multipart/form-data">
					@csrf
					<div class="row form-section" id="section-1">
						<div class="col-xl-12">
							<div class="submit-field">
								<h5>Select a name for the job</h5>
								<input type="text" name="title" class="with-border" value="{{ old('title') }}" placeholder="e.g. I need a blog" id="title">
							</div>
						</div>
						<div class="col-xl-12">
							<div class="submit-field">
								<h5>Give a description of what the job entails</h5>
								<textarea 
								name="description"
									cols="30" 
									rows="5" 
									class="with-border" 
									placeholder="Provide an accurate and detailed description that best suits the proposed job" 
									id="description"
								>{{ old('description') }}</textarea>
							</div>
						</div>
						<div class="col-xl-12">
							<button class="btn btn-outline-secondary next-btn" type="button">NEXT</button>
						</div>
					</div>
					
					<div class="row d-none form-section" id="section-2">

						{{-- <div class="col-xl-6">
							<div class="submit-field">
								<h5>Job Type</h5>
								<select class="selectpicker with-border" data-size="7" title="Select Job Type" id="type">
									<option>Short Term</option>
									<option>Long Term</option>
								</select>
							</div>
						</div> --}}

						<div class="col-xl-12">
							<div class="submit-field">
								<h5>Job Category</h5>
								<select class="selectpicker with-border" data-size="7" title="Select Category" id="category" name="industry_id">									
									@foreach ($categories as $category)
									<option value="{{ $category->id }}" {{ $category->id == old('industry_id') ? 'selected="selected"' : '' }}>{{ $category->title }}</option>
									@endforeach									
								</select>
							</div>
						</div>

						<div class="col-xl-12">
							<button class="btn btn-outline-secondary next-btn" type="button">NEXT</button>
						</div>

					</div>

					<div class="row d-none animated fadeInUp form-section" id="section-3">
						<div class="col-xl-12">
							<div class="submit-field">
								<h5>Skills <i class="help-icon" data-tippy-placement="right" title="Enter up to 5 skills that best describe your project. Freelancers will use these skills to find projects they are most interested and experienced in."></i></h5>

								<select class="selectpicker with-border z-200 relative" name="skills[]" multiple data-live-search="true" id="skills">								
									@foreach ($skills as $skill)
										<option value="{{ $skill->id }}">{{ $skill->title }} </option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-xl-12">
							<div class="submit-field">
								<h5>Budget Type</h5>
								<div class="row">
									<div class="col-xl-12">
										<select class="selectpicker with-border" id="pricing" name="budget_type">
											<option value="fixed" {{ 'fixed' == old('budget_type') ? 'selected="selected"' : '' }}>Fixed Price</option>
											<option value="hourly">Hourly Price</option>
										</select>										
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-12">
							<div class="submit-field">
								<h5>Estimated Budget</h5>
								<div class="row">
									<div class="col-xl-3">
										<select class="selectpicker with-border" id="currency" name="currency">
											<option>USD</option>
											{{--  <option>GHS</option>
											<option>EUR</option>
											<option>CAD</option>
											<option>INR</option>
											<option>GBP</option>
											<option>AUD</option>
											<option>HKD</option>
											<option>NZD</option>  --}}
										</select>
									</div>
									<div class="col-xl-9">
										<select class="selectpicker with-border" id="budget_type_price">								
											@foreach ($budgetTypes as $budgetType)
												<option value="{{ $budgetType->id }}">{{ $budgetType->name }} (${{ $budgetType->from }} - {{ $budgetType->to }} USD)</option>
											@endforeach
											<option value="custom">Customize  Budget</option>
										</select>
										
									</div>
									<div class="col-xl-12 my-3 d-none animated fadeInDown" id="custom_budget_section">
										<div class="row">
											<div class="col-6">
												<div class="submit-field">
													<h5>Minimum Budget</h5>
													<input type="number" name="min_budget" value="{{ old('min_budget') ?? 0 }}" class="with-border" id="min_budget">
												</div>														
											</div>
											<div class="col-6">
												<div class="submit-field">
													<h5>Maximum Budget</h5>
													<input type="number" name="max_budget" value="{{ old('max_budget') ?? 0 }}" class="with-border" id="max_budget">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>


						<div class="col-xl-12">
							<div class="submit-field">
								<div class="uploadButton margin-top-30">
									<input class="uploadButton-input" type="file" accept="image/*, application/pdf, application/txt, application/doc,application/docx,application/csv,application/xlsx" name="documents[]" id="upload" multiple/>
									<label class="uploadButton-button ripple-effect" for="upload">Upload Files</label>
									<span class="uploadButton-file-name">Images or documents that might be helpful in describing your job</span>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="col-md-6 offset-md-3">
		<button type="submit" class="button z-10 ripple-effect big margin-top-30 d-none" id="submitBtn"><i class="icon-feather-plus"></i> Post a Job</button>
	</div>

</div>
@endrole
@endsection

@push('custom-scripts')
    <script>
		$(document).ready(function(){
			// Set minMax
			setMinMaxValues(1);
			// Dropdown
			var budgetType = $('#budget_type_price');
			budgetType.on("change", function(){
				var selected = $(this).find(":selected").val();
				var customBudgetSection = $('#custom_budget_section');


				if(selected == 'custom'){
					customBudgetSection.removeClass('d-none');
				} else {
					customBudgetSection.addClass('d-none');
					// Set Min and Max Values
					setMinMaxValues(selected);
				}
			});

			function setMinMaxValues(selectedBudgetType){
				var budgetTypes = {!! json_encode($budgetTypes) !!};
				
				var minBudget = $('#min_budget');
				var maxBudget = $('#max_budget');
				var selected = $.grep(budgetTypes, function(e){ return e.id == selectedBudgetType; })[0];
				minBudget.val(selected.from);
				maxBudget.val(selected.to);
			}

			// Button
			var nextBtn = $('.next-btn');
			nextBtn.on("click", function(){
				var closestSection = $(this).closest('.form-section');
				var csID = closestSection.attr('id');
				var sectionId = csID.split("-")[1];

				var _isValidSection = isValidSection(sectionId); // true;// ;

				if(_isValidSection){
					// Hide BTN 
					$(this).addClass('d-none');
					// Show next section
					var nextSectionId = parseInt(sectionId) + 1;
					$('#section-' + nextSectionId).removeClass('d-none');
				}
			});

			
			// Listen for submit button click
			var submitBtn = $('#submitBtn');
			submitBtn.on("click", function(){
				var skills = $('#skills').val();
				if(isEmpty(skills)){
					showError("Select one of more skills for the job!");
				} else {
					if(isValidSection(1) && isValidSection(2)){
						$('#post_job_form').submit();
					}
				}
			});





			// Functions
			function isValidSection(sectionId){
				// Fields
				var title = $('#title').val();
				var description = $('#description').val();

				var category = $('#category').val();


				var skills = $('#skills').val();

				if(sectionId == 1){
					if(isEmpty(title)){
						showError("The job name is required!");
						return false;
					} else if(!isValidLength(title, 6)){
						showError("The job name should be at least 6 characters");
					} else if(isEmpty(description)){
						showError("The job description is required!");
					} else if(!isValidLength(description, 6)){
						showError("The job description should be at least 6 characters");
					}
					else {
						return true;
					}
				} else if(sectionId == 2){

					if(isEmpty(category)){
						showError("The job category is required!");
						return false;
					} else {
						$('#submitBtn').removeClass('d-none');
						return true;
					}
				} else {
					return true;
				}
			}

			function isEmpty(val){
				return val.length == 0 ? true : false;
			}

			function isValidLength(field, length){
				let noSpace = field.replace(/ /g, "");
				return noSpace.length >= length ? true : false;
			}

			function showError(message){
				Snackbar.show({
					text: message,
					pos: 'top-right',
					showAction: false,
					actionText: "Dismiss",
					duration: 3000,
					textColor: '#fff',
					backgroundColor: '#383838'
				});
			}

		});
	</script>
@endpush