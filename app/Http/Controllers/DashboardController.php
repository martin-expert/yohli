<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Job;
use App\Models\Skill;
use App\Models\Invite;
use App\Models\Country;
use App\Models\Profile;
use App\Models\Bookmark;
use App\Models\Milestone;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use CyrildeWit\EloquentViewable\View;
use CyrildeWit\EloquentViewable\Support\Period;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        // Get Jobs the User has Completed
        if ($user->hasRole('hirer')) {
            $count_ongoing_jobs = $jobs = Job::where('status', 'assigned' )->where('user_id', Auth::id())->count();
            $pending_bids = Job::where('user_id', $user->id)->where('status', 'not assigned' )->withCount('bids')->get('id');
            $pending_bids = $pending_bids->sum('bids_count');
            $completed_jobs = $jobs = Job::where('status', 'completed' )->where('user_id', Auth::id())->count();
            $jobs = $jobs = Job::where('user_id', Auth::id())->with('profile')->limit('4')->latest()->get();

            $monthly_views = views($user->profile)->period(Period::since(now()->startOfMonth()))->count();

            $profile_views = $this->profile_views($user->profile);
            
            return view('dashboard.dashboard', compact('count_ongoing_jobs', 'pending_bids', 'completed_jobs', 'jobs', 'profile_views', 'monthly_views'));

        } else {
            $jobs = Job::where('profile_id', Auth::user()->profile->id)->limit(4)->latest()->get();

            $completed_jobs = Job::where('profile_id', Auth::user()->profile->id)->where('status', 'completed' )->count();


            $bids_count = Bid::where('profile_id', $user->profile->id)->where('status', 'pending')->whereMonth('created_at', now()->copy()->format('m'))->count();
            $monthly_views = views($user->profile)->period(Period::since(now()->startOfMonth()))->count();

            $profile_views = $this->profile_views($user->profile);

            $profile = $user->profile;

            $ongoing_jobs_count = Job::where('profile_id', Auth::user()->profile->id)->where('status', 'assigned' )->count();

            return view('dashboard.dashboard', compact('profile_views', 'jobs', 'monthly_views', 'bids_count', 'profile', 'ongoing_jobs_count', 'completed_jobs'));
        }
       
    }

    private function profile_views($user)
    {
        $user =  View::where('viewable_id', $user->id)
        ->where('viewable_type', 'App\Models\Profile')
        ->selectRaw('count(id) as `total`')
        ->selectRaw("count(case when (MONTH(viewed_at) = 1 AND YEAR(viewed_at) = YEAR(CURDATE() ) ) then 1 end) as `jan`")  
        ->selectRaw("count(case when (MONTH(viewed_at) = 2 AND YEAR(viewed_at) = YEAR(CURDATE()) ) then 1 end) as `feb`")  
        ->selectRaw("count(case when (MONTH(viewed_at) = 3 AND YEAR(viewed_at) = YEAR(CURDATE()) ) then 1 end) as `mar`")  
        ->selectRaw("count(case when (MONTH(viewed_at) = 4 AND YEAR(viewed_at) = YEAR(CURDATE()) ) then 1 end) as `apr`")  
        ->selectRaw("count(case when (MONTH(viewed_at) = 5 AND YEAR(viewed_at) = YEAR(CURDATE()) ) then 1 end) as `may`")  
        ->selectRaw("count(case when (MONTH(viewed_at) = 6 AND YEAR(viewed_at) = YEAR(CURDATE()) ) then 1 end) as `jun`")  
        ->selectRaw("count(case when (MONTH(viewed_at) = 7 AND YEAR(viewed_at) = YEAR(CURDATE()) ) then 1 end) as `jul`")  
        ->selectRaw("count(case when (MONTH(viewed_at) = 8 AND YEAR(viewed_at) = YEAR(CURDATE()) ) then 1 end) as `aug`")  
        ->selectRaw("count(case when (MONTH(viewed_at) = 9 AND YEAR(viewed_at) = YEAR(CURDATE()) ) then 1 end) as `sep`")  
        ->selectRaw("count(case when (MONTH(viewed_at) = 10 AND YEAR(viewed_at) = YEAR(CURDATE()) ) then 1 end) as `oct`")  
        ->selectRaw("count(case when (MONTH(viewed_at) = 11 AND YEAR(viewed_at) = YEAR(CURDATE()) ) then 1 end) as `nov`")  
        ->selectRaw("count(case when (MONTH(viewed_at) = 12 AND YEAR(viewed_at) = YEAR(CURDATE()) ) then 1 end) as `dec`")  
        ->first();

        return $user;
    }

    public function settings(Request $request)
    {
        $myUser = Auth::user();
        $user = Profile::where('user_id', Auth::id())->with('media')->first();
        $countries = Country::get();
        $skills = Skill::orderBy('title', 'asc')->get();

        $payment_method = $myUser->defaultPaymentMethod();

        $intent = $myUser->createSetupIntent();

        if($myUser->hasPaymentMethod()){
            $card = $myUser->defaultPaymentMethod()->card;
            // dd($myUser->defaultPaymentMethod());
        }else{
            $card = "";
        }
      

        return view('dashboard.settings', compact('countries', 'user', 'skills', 'intent', 'payment_method', 'card'));
    }

    public function verify()
    {
        if(!empty(Auth::user()->profile_verified_at)){
            toastr()->error('Profile is already verified');
            return redirect('settings'); 
        }
        return view('dashboard.verify');
    }
    
    public function invites()
    {
        $user = Auth::user();
        $freelancer = Profile::where('user_id', Auth::id())->first();

        if($user->hasRole('hirer')){
            $invites = Invite::where('user_id', $user->id)
                ->with('job', 'profile')
                ->get();
        } else {
            $invites = Invite::where('profile_id', $freelancer->id)
            ->with('job', 'profile')
            ->get();
        }

        

        return view('dashboard.jobs.invites', compact('invites'));
    }


    public function milestones(Request $request, $slug)
    {
        $user = Auth::user();
        $freelancer = Profile::where('user_id', Auth::id())->first();

        $job = Job::where('slug', $slug)
                    ->with('accepted_bid')->first();


        if(empty($job)){
            abort(404);
        }
        $bidRate = 0;        
        $jobMilestones = 0;

        if($user->hasRole('hirer')){
            $m = Milestone::where('job_id', $job->id)
            ->with(['profile'])
            ->where('user_id', $user->id);

            $done = Milestone::where('job_id', $job->id)
            ->where('status', 'done')
            ->where('user_id', $user->id)
            ->count();

            $paid = Milestone::where('job_id', $job->id)
            ->where('is_paid', '1')
            ->where('user_id', $user->id)
            ->count();
        } else {
            $m = Milestone::where('job_id', $job->id)
            ->with('profile')
            ->where('profile_id', $freelancer->id);

            $done = Milestone::where('job_id', $job->id)
            ->where('status', 'done')
            ->where('profile_id', $freelancer->id)
            ->count();

            $paid = Milestone::where('job_id', $job->id)
            ->where('is_paid', '1')
            ->where('profile_id', $freelancer->id)
            ->count();

            $bidRate = Bid::where('profile_id', $freelancer->id)
            ->where('job_id', $job->id)->first()->rate ?? 0;

            $jobMilestones = Milestone::where('profile_id', $freelancer->id)
            ->where('job_id', $job->id)->sum('cost');
        }


        $milestones = $m->oldest()->get();
        $mCount = $m->count();

        $completion = 0;
        $payment = 0;

        if($mCount > 0){
            $completion = ($done / $mCount ) * 100;
            $payment = ($paid / $mCount ) * 100;

            // Set job to completed if its 100
            if($payment == 100 ){
                $job->status = 'completed';
                $job->save();

                $conversation = Conversation::where('job_id', $job->id)->first();
                // $request->user()->leaveConversation($conversation->id);
                if($conversation)
                {
                    $conversation->job_id = NULL;
                    $conversation->save();
                }
            }
        }

        $available = $bidRate - $jobMilestones;

        return view('dashboard.milestones', compact('milestones', 'job', 'completion','payment', 'available')); 
    }

    public function bidders(Request $request, $slug)
    {
        $user = Auth::user();
        $job = Job::where('slug', $slug)->with(['bids'])->first();

        if(empty($job)){
            abort(404);
        }

        return view('dashboard.bidders', compact('job'));
    }


}
