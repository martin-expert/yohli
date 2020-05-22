@extends('layouts.master')
@section('title', $job->title)

@section('content')

<!-- Titlebar
================================================== -->
<div class="single-page-header" data-background-image="{{ asset('assets/images/single-task.jpg') }}">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="single-page-header-inner">
          <div class="left-side">
            <div class="header-image p-0">
              @if (sizeof($job->owner->profile->getMedia('profile')) == 0)
               <img class="" src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt="">
              @else
                <img src="{{ $job->owner->profile->getFirstMediaUrl('profile', 'big') }}" alt=""/> 
              @endif  
            </div>
            <div class="header-details">
              <h3>{{ $job->title ?? '' }}</h3>
              <h5>About the Employer</h5>
              <ul>
                <li><a href="../companies/{{ $job->owner->profile->uuid ?? '' }}"><i class="icon-feather-user"></i>{{ $job->owner->name ?? '' }}</a></li>
                <li><div class="star-rating" data-rating="{{ $job->owner->rating ?? 0 }}"></div></li>
                <li><img class="flag" src="{{ asset('assets/images/flags/'. strtolower($job->owner->profile->country->code.'.svg')) }}" alt="$job->owner->profile->country->name"> {{ $job->owner->profile->country->name }}</li>
                
                @if($job->owner->verified == 1)
                <li><div class="verified-badge-with-title">Verified</div></li>
                @endif
              </ul>
            </div>
          </div>
          <div class="right-side">
            <div class="salary-box">
              <div class="salary-type">@if($job->budget_type == 'fixed')
                Fixed Price
              @else
                Hourly Rate
              @endif</div>
              <div class="salary-amount"> 
              @if ($job->min_budget == $job->max_budget)
                {{ '$'.$job->min_budget }}
              @else
                {{ '$'.$job->min_budget. ' - $' .$job->max_budget }}
              @endif</div>
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
      
      <!-- Description -->
      <div class="single-page-section">
        <h3 class="margin-bottom-25">Project Description</h3>
        <div>
          {!! $job->description !!}
        </div>
      </div>

       <!-- Atachments -->
      <div class="single-page-section">
        <h3>Attachments</h3>
        
        <div class="attachments-container">
        @forelse ($job->getMedia('project_files') as $file)
          <a href="../storage/{{ $file->id }}/{{ $file->file_name }}" target="new" class="attachment-box ripple-effect cursor-pointer">
            <span class="text-capitalize hover:text-white ">{{ $file->name }} </span>
            <i class="text-uppercase hover:text-white">{{ $file->extension }}</i> 
          </a>

          @empty
        None
        @endforelse
        </div>
        
      
      </div> 

      <!-- Skills -->
      <div class="single-page-section">
        <h3>Skills Required</h3>
        <div class="task-tags">
          @foreach ($job->skills as $skill)
          <span>{{ $skill->title }}</span>
          @endforeach
        </div>
      </div>
      <div class="clearfix"></div>
      
      <!-- Freelancers Bidding -->
      <div class="boxed-list margin-bottom-60">
        <div class="boxed-list-headline">
          <h3><i class="icon-material-outline-group"></i> Current Bids</h3>
        </div>
        <ul class="boxed-list-ul">
        @foreach($bids as $bid)
          <li>
            <div class="bid">
              <!-- Avatar -->
              <div class="bids-avatar">
                <div class="freelancer-avatar">
                  <div class="verified-badge"></div>
                  @if (sizeof($bid->profile->getMedia('profile')) == 0)
                  <img src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt="">
                  @else
                    <img src="{{ $bid->profile->getFirstMediaUrl('profile', 'big') }}" alt=""/> 
                  @endif  
                </div>
              </div>
              
              <!-- Content -->
              <div class="bids-content">
                <!-- Name -->
                <div class="freelancer-name">
                  <h4><a href="../freelancers/{{ $bid->profile->uuid }}"> {{ $bid->profile->name }} <img class="flag" src="{{ asset('assets/images/flags/'. strtolower($bid->profile->country->code.'.svg')) }}"  title="{{ $bid->profile->country->name }}" data-tippy-placement="top" alt=""> {{ $bid->profile->country->name ?? "N/A" }} </a></h4>
                  <div class="star-rating" data-rating="{{ $bid->profile->rating ?? 0 }}"></div>
                  @if(Auth::user()->profile->type == 'hirer')
                    <div class="bid-status">
                      @if ($bid->status == 'accepted')
                        <span class="status-badge status-accepted">accepted</span>
                      @else
                        <span class="status-badge status-pending">pending...</span>
                      @endif
                    </div>
                  @endif
                </div>
              </div>
              
              <!-- Bid -->
              <div class="bids-bid">
                <div class="bid-rate">
                  <div class="rate">${{ $bid->rate }}</div>
                  <span>in {{ $bid->delivery_time }} {{ $bid->delivery_type }}</span>
                </div>
              </div>
            </div>
            @if(Auth::user()->profile->type == 'hirer')
              <div class="bid-actions">
                @if ($bid->status != 'accepted')
                  <a href="#small-dialog-1" data-bid="{{ $bid }}" class="btn btn-success button-accept popup-with-zoom-anim button ripple-effect js-bid-action" data-action="accept">
                    <i class="icon-material-outline-check"></i> Accept Offer
                  </a>
                  <!-- <button class="btn btn-default button-ignore js-bid-action" data-action="ignore">
                    Ignore
                  </button> -->
                @endif
              </div>
            @endif
          </li>
        @endforeach
        </ul>
      </div>

    </div>
    

    <!-- Sidebar -->
    <div class="col-xl-4 col-lg-4">
      <div class="sidebar-container">

        
        @if($job->owner->id != Auth::id())
        @role('freelancer')
        <div class="sidebar-widget">
          <div class="bidding-widget">
            <div>@include('partials.alert')</div>
            @if(!$hasPlacedBid)
            <div>
              <div class="bidding-headline"><h3>Bid on this job!</h3></div>
              <div class="bidding-inner">

                <!-- Headline -->
                <span class="bidding-detail">Set your <strong>minimal rate</strong></span>

                <form method="post" action="/make_bid/{{ $job->uuid }}">
                  @csrf
                  <!-- Price Slider -->
                
                  <div class="flex">
                    <div class="bidding-value">$<span id="biddingVal"></span></div>
                    @if($job->budget_type == 'fixed')
                    <span class="text-sm">Fixed</span>
                    @else
                      <span class="text-sm">/hr</span>  
                    @endif                  
                  </div>
                  <input class="bidding-slider" name="rate" type="text" value="{{  $job->min_budget }}" data-slider-handle="custom" data-slider-currency="$" data-slider-min="{{ $job->min_budget }}" data-slider-max="{{ $job->max_budget }}" data-slider-value="auto" data-slider-step="10" data-slider-tooltip="hide" />

                
                  <!-- Headline -->
                  <span class="bidding-detail margin-top-30">Set your <strong>delivery time</strong></span>

                  <!-- Fields -->
                  <div class="bidding-fields">
                    <div class="bidding-field">
                      <!-- Quantity Buttons -->
                      <div class="qtyButtons">
                        <div class="qtyDec"></div>
                        <input type="text" name="delivery_time" value="1">
                        <div class="qtyInc"></div>
                      </div>
                    </div>
                    <div class="bidding-field">
                      <select class="selectpicker default" name="delivery_type">
                        @if($job->budget_type == 'fixed')
                        <option value="days" selected>Days</option>
                        <option value="hours">Hours</option>
                        @else
                        <option value="hours">Hours</option>  
                        @endif  
                        
                      </select>
                    </div>
                  </div>

                  <!-- Headline -->
                  <span class="bidding-detail margin-top-30">Proposal</strong></span>
                  <div class="bidding-fields">
                    <div class="bidding-field">
                      <textarea name="description"></textarea>
                    </div>
                  </div>

                  <!-- Button -->
                  <button type="submit" id="snackbar-place-bid" class="button ripple-effect move-on-hover full-width margin-top-30">
                    <span>Place a Bid</span>
                  </button>
                </form>
              </div>
            {{-- <div class="bidding-signup">Don't have an account? <a href="#sign-in-dialog" class="register-tab sign-in popup-with-zoom-anim">Sign Up</a></div> --}}
            </div>
            @else
            <div class="notification primary">
                <p class="text-center" >
                  You have already bid for this job
                </p>
                <a class="close" href="#"></a>
              </div>
            @endif
          </div>
        </div>
        @endrole
        @endif

        
        <div class="sidebar-widget">
          <!-- Sidebar Widget -->
          @if($job->owner->id != Auth::id())
          @role('freelancer')
          <h3>Bookmark</h3>
          <button class="bookmark-button margin-bottom-25 {{ $isBookmakedByUser == 1 ? 'bookmarked' : '' }}" onclick="bookmark({{ $job->id }})">
            <span class="bookmark-icon"></span>
            <span class="bookmark-text">Bookmark</span>
            <span class="bookmarked-text">Bookmarked</span>
          </button>
          
          @endrole
          @endif

          <h3>Share</h3>
          <!-- Copy URL -->
          <div class="copy-url">
            <input id="copy-url" type="text" value="" class="with-border">
            <button class="copy-url-button ripple-effect" data-clipboard-target="#copy-url" title="Copy to Clipboard" data-tippy-placement="top"><i class="icon-material-outline-file-copy"></i></button>
          </div>

          <!-- Share Buttons -->
          {{--  <div class="share-buttons margin-top-25">
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
          </div>  --}}
        </div>

      </div>
    </div>

  </div>
</div>

@if(Auth::user()->profile->type == 'hirer')
  <!-- Bid Acceptance Popup for hirer
  ================================================== -->
  <div id="small-dialog-1" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

    <!--Tabs -->
    <div class="sign-in-form">
      <ul class="popup-tabs-nav">
        <li><a href="#tab1">Accept Offer</a></li>
      </ul>
      <div class="popup-tabs-container">
        <!-- Tab -->
        <div class="popup-tab-content" id="tab">          
          <!-- Welcome Text -->
          <div class="welcome-text">
            <h3 id="acceptBidText">Accept Offer From {{ $bid->profile->name ?? '' }} </h3>
            <div class="bid-acceptance margin-top-15" id="bidPrice">
              ${{ $bid->rate ?? '' }} 
            </div>
          </div>

          <form id="acceptBidForm" action="" method="post">
                      @csrf
            {{-- <div class="radio">
              <input id="radio-1" name="radio" type="radio" required>
              <label for="radio-1"><span class="radio-label"></span>  I accept the bid</label>
                      </div> --}}
            <input type="hidden" name="profile_id" id="aProfileId">
          </form>
          <!-- Button -->
          <button class="margin-top-15 button full-width button-sliding-icon ripple-effect" type="submit" form="acceptBidForm">Accept <i class="icon-material-outline-arrow-right-alt"></i></button>
        </div>
      </div>
    </div>
  </div>
  <!-- Bid Acceptance Popup / End -->
@endif

<script src="{{ asset('js/app.js') }}"></script>

<script>
  function bookmark(id){
    axios.post('bookmarks-toggle-api', {
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

@push('custom-scripts')
  <script type="text/javascript">
    $('.js-bid-action[data-action]').click(function() {
      switch($(this).attr('data-action')) {
        case 'accept' :
          var _bid = $(this).attr("data-bid");
          if (isJson(_bid)) {
            var bid = JSON.parse(_bid);

            $('#bidPrice').text('$'+ThousandSeparator2(bid.rate));
            $('#acceptBidText').text('Accept Offer From '+bid.profile.name);
            $('#aProfileId').val(bid.profile.id);
            $('#acceptBidForm').attr('action', '/bidders/accept_bid/'+bid.uuid);
          }
        break;
        case 'ignore' :
        break;
        default:
        break;
      }
    });

    function isJson(str) {
      try {
        JSON.parse(str)
        return true;
      } catch {
        return false;
      }
    }

    function ThousandSeparator2(nStr) {
      nStr += '';
      var x = nStr.split('.');
      var x1 = x[0];
      var x2 = x.length > 1 ? '.' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
      }
      return x1 + x2;
    }

  </script>
@endpush