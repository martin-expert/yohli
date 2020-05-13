@extends('layouts.dashboard_master')
@section('title', 'Job Invites')

@section('content')
<div id="vue-app">
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">

            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-gavel"></i> Job Invites</h3>
            </div>

            <div class="content">
                <ul class="dashboard-box-list">
                    @forelse ($invites as $invite)
                    <li>
                            <!-- Job Listing -->
                            <div class="job-listing width-adjustment">
    
                                <!-- Job Listing Details -->
                                <div class="job-listing-details">
    
                                    <!-- Details -->
                                    <div class="job-listing-description">
                                        <h3 class="job-listing-title"><a href="{{ route('jobs.show', $invite->job->slug) }}">{{ $invite->job->title }}</a></h3>
                                            <div class="flex flex-row">
                                                <button class="bg-green-500 text-white p-1 px-4 rounded shadow">{{ strtoupper($invite->status) }}</button>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Task Details -->
                            <ul class="dashboard-task-info">
                            @foreach ($invite->getMedia('project_files') as $file)
                            <li>
                                <a href="storage/{{ $file->id }}/{{ $file->file_name }}" target="new" >
                                    <div class="attachment-box hover:bg-orange-500 hover:text-white cursor-pointer">
                                        <span class="text-capitalize hover:text-white ">{{ $file->name }} </span>
                                        <i class="text-uppercase hover:text-white">{{ $file->extension }}</i>
                                    </div> 
                                </a>
                            </li>
                            @endforeach
                                <li><strong>${{ $invite->job->min_budget ==  $invite->job->max_budget ? $invite->job->min_budget : $invite->job->min_budget. " - " .$invite->job->max_budget }}</strong><span>{{ $invite->job->budget_type == 'fixed' ? 'Fixed' : 'Hourly' }} rate</span></li>
                            </ul> 
    
                            <!-- Buttons -->
                            @role('freelancer')
                            <div class="buttons-to-right always-visible">
                                @if($invite->status != 'rejected' && $invite->status != 'accepted')
                                <a @click="set_invite({{ $invite }})"  href="#small-dialog" class="button primary popup-with-zoom-anim  ripple-effect ico" title="Accept Invite" data-tippy-placement="top"><i class="icon-feather-check-circle"></i></a>
                                <a href="#" @click="reject_invite('{{ $invite->uuid }}')" class="button red ripple-effect ico" title="Reject Invite" data-tippy-placement="top"><i class="icon-line-awesome-close"></i></a>
                                @endif                               
                            </div>
                            @endrole
                        </li> 
                    @empty
                    <p class="text-center text-muted py-3">
                        @role('hirer')
                        YOU HAVE SENT NO INVITE
                        @endrole
                        @role('freelancer')
                        YOU HAVE NO INVITE
                        @endrole
                    </p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Edit Bid Popup
================================================== -->
<div id="small-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab">Accept Invite</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				<!-- Bidding -->
				<div class="bidding-widget">
                    <div @click="error = false" v-show="error" class="cursor-pointer notification error closeable">
                        <p>Please fill in all the fields required</p>
                    </div>
						<!-- Headline -->
						<span class="bidding-detail">Set your <strong>minimal rate</strong></span>

						<!-- Price Slider -->
						<div class="bidding-value">$<span ref="rate_value" id="biddingVal"></span></div>
						<input v-model="rate"  name="rate" id="bid_price" class="bidding-slider" type="text"  data-slider-handle="custom" data-slider-currency="$" data-slider-min="10" data-slider-max="1000" data-slider-value="1" data-slider-step="1" data-slider-tooltip="hide" required/>
						
						<!-- Headline -->
						<span class="bidding-detail margin-top-30">Set your <strong>delivery time</strong></span>

						<!-- Fields -->
						<div class="bidding-fields">
							<div class="bidding-field">
								<!-- Quantity Buttons -->
								<div class="qtyButtons with-border">
									<div @click="(delivery_time--)" class="qtyDec"></div>
									<input  v-model="delivery_time"  type="text" name="delivery_time" id="delivery_time" required>
									<div @click="(delivery_time++)"  class="qtyInc"></div>
								</div>
							</div>
							<div class="bidding-field">
								<select  v-model="delivery_type"  class="" name="delivery_type" id="delivery_type" required>
									<option value="days" selected>Days</option>
									<option value="hours">Hours</option>
								</select>
							</div>
						</div>
                        <div class="bidding-field mt-6">
                            <textarea  v-model="description"  name="description" id="description" placeholder="how will you solve the problem?"></textarea>
                        </div>
				</div>
				
				<!-- Button -->
				<button @click="accept_invite()" type="submit" class="button full-width button-sliding-icon ripple-effect" type="submit">Save Changes <i class="icon-material-outline-arrow-right-alt"></i></button>
			</div>

		</div>
	</div>
</div>
</div>
<!-- Edit Bid Popup / End -->

<script src="{{ asset('js/app.js') }}"></script>

<script>


var vm = new Vue({
    el: '#vue-app',
    data: {
        invite: {},
        description: '',
        rate: 10,
        delivery_time: 0,
        delivery_type: 'days',
        job: '',
        error: false,
    },

    methods: {
        reject_invite(uuid){
            var result = confirm('Do you want to Reject Invite');
            if(result){
                axios.get("{{ url('reject-invite') }}/" + uuid)
                    .then(function(r){
                    window.location.reload();
                })
             }
        },

        accept_invite(){
            this.rate = this.$refs.rate_value.innerText;
            if(this.rate <= 0 || this.delivery_time <= 0 || this.delivery_type == '')
            {
                return this.error = true;
            }

            axios.put("{{ url('accept-invite') }}/" + this.invite.uuid, {
                description: this.description,
                rate: this.rate,
                delivery_time: this.delivery_time,
                delivery_type: this.delivery_type,
                job: this.invite.job_id,
            }).then(function(r){
                this.description = '';
                this.delivery_type = '';
                this.delivery_time = '';
                this.rate = '';
                window.location.reload();
            })
        },

        set_invite(data){
            this.invite = data;
        }



    }
})

</script>
@endsection