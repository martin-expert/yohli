<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\JobsController;

class ApplicationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application home.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(JobsController $jobs)
    {
        $recent_featured_jobs = $jobs->recent_featured_jobs(6);

        $job_categories = $jobs->job_categories(8);
        
        $total_jobs = DB::table('jobs')
                    ->selectRaw('count(*) as total')
                    ->selectRaw("count(case when status = 'completed' then 1 end) as completed")
                    ->first();

        $total_freelancers = User::role('freelancer')->count();   
        
        $freelancers = Profile::where('type', 'freelancer')->with('skills', 'country', 'reviews')->get();

        return view('home', compact('recent_featured_jobs', 'job_categories',
                                    'total_jobs', 'total_freelancers', 'freelancers'));
    }
}
