<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Profile;
use App\Models\Bookmark;
use App\Models\Country;
use App\Models\Invite;
use App\Models\Job;
use App\Models\Milestone;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      // $plan->pivot->created_at = "2021-04-21 16:31:59";
      // $plan->pivot->save();

      $users = User::all();
      return response()->json($users);
    }
}
