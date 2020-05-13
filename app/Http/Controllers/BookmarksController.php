<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $freelancer = Profile::where('user_id', Auth::id())->first();

        if ($freelancer->type == 'hirer') {
            $bookmarks = Bookmark::where('user_id', Auth::id())
                ->with('freelancer')
                ->get();
        } else {
            $bookmarks = Bookmark::where('user_id', $user->id)
                ->with('job')
                ->get();
        }


        // return $bookmarks;

        return view('dashboard.bookmarks', compact('bookmarks'));
    }

    public function toggle_api(Request $request)
    {
        $_bookmark = Bookmark::where(['user_id' => Auth::id(), 'job_id' => $request->job_id])
            ->orwhere(['user_id' => Auth::id(), 'profile_id' => $request->profile_id])
            ->first();

        if (!empty($_bookmark)) {
            $_bookmark->delete();
            return response()->json(["message" => "Bookmark removed successfully"]);
        } else {
            $bookmark = new Bookmark;
            $bookmark->user_id = Auth::user()->id;
            $bookmark->profile_id = $request->profile_id;
            $bookmark->job_id = $request->job_id;
            $bookmark->save();

            return response()->json(["message" => "Bookmarked successfully"]);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bookmark = Bookmark::find($id);
        $bookmark->delete();

        return back()->with('success', 'Bookmark removed');
    }
}
