<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');






Route::namespace("Auth")->group(function () {
    Route::get('login/{provider}', 'LoginController@redirectToProvider');
    Route::get('login/{provider}/callback', 'LoginController@handleProviderCallback');
});

Route::get('/', 'ApplicationController@index')->name('home');

Route::get('how-it-works', function () {
    return view('how_it_works');
})->name('how-it-works');

Route::get('companies/{id}', function($id){
    $profile = \App\Models\Profile::where('uuid', $id)->first();

    if (empty($profile)) {
        abort(404);
    }
    views($profile)->delayInSession(10)->record();    

    $jobs =  \App\Models\Job::where('status', 'not assigned')
    ->where('user_id', $profile->user_id)
    ->get();

    $reviewed_jobs =  \App\Models\Job::where('status', 'completed')
    ->where('user_id', $profile->user_id)
    ->get();

    $reviews = $reviewed_jobs->map(function($item,$value){
        return [
            $item->reviews()->latest()->limit('20')->get()
        ];
    })->flatten()->all();


    return view('company', compact('profile', 'jobs', 'reviews'));
});

// Jobs
Route::get('jobs-api', 'JobsController@jobs');
Route::get('job-categories-api', 'JobsController@industries');
Route::get('skills-api', 'JobsController@skills');
// Route::get('post-job', 'JobsController@create')->name('post-job');

// Freelancers
Route::get('freelancers-api', 'FreelancersController@freelancers');
Route::get('countries-api', 'FreelancersController@countries');

// Blog
Route::resource('blog', 'BlogController')->only('index', 'show');
Route::get('posts', 'BlogController@posts');
Route::get('posts/tags', 'BlogController@tags');
Route::get('posts/categories', 'BlogController@categories');
Route::get('posts/featured', 'BlogController@featured_posts');
Route::get('posts/trending', 'BlogController@trending_posts');



// DASHBOARD STUFF
Route::group(['middleware' => ['auth', 'verified']], function () {
    //chat
    Route::get('chats', 'ChatController@index')->name('chats'); 
    Route::get('conversations', 'ChatController@index_json'); 
    Route::post('chats/{id}', 'ChatController@send'); 
    Route::put('chats/{id}/markseen', 'ChatController@markSeen'); 
    // Profile Settings
    Route::post('update_password', 'AccountController@update_password');
    Route::post('update_basic_info', 'AccountController@update_basic_info');
    Route::post('update_freelancer_info', 'AccountController@update_freelancer_info');
    Route::post('update_freelancer_documents', 'AccountController@update_freelancer_documents');
    Route::post('update_role', 'AccountController@update_role');
    Route::post('update_withdrawal_info', 'AccountController@update_withdrawal_info');

    // Freelancers
    Route::get('browse-freelancers', 'FreelancersController@index')->name('freelancers.index');
    Route::resource('freelancers', 'FreelancersController')->except('index');

    // Hirer 
    Route::get('browse-jobs', 'JobsController@index')->name('jobs.index');
    Route::resource('jobs', 'JobsController')->except('index');
    Route::get('post-job', 'JobsController@create')->name('post-job');
    Route::post('search-input-text', 'JobsController@saveSearchVal')->name('search.text');
    
    // Freelancer Stuff
    Route::group(['middleware' => ['role:freelancer']], function () {
        Route::get('my-bids', 'FreelancersController@bids')->name('my-bids');
        Route::get('reject-invite/{uuid}', 'FreelancersController@reject_invite')->name('reject.invite');
        Route::put('accept-invite/{uuid}', 'FreelancersController@accept_invite')->name('accept.invite');
        Route::post('make_bid/{uuid}', 'FreelancersController@make_bid');
        Route::post('edit_bid/{uuid}', 'FreelancersController@edit_bid');
        Route::post('delete_bid/{uuid}', 'FreelancersController@delete_bid');
        Route::post('review_job/{uuid}', 'FreelancersController@review_job');
        Route::post('milestones/update_milestone/{uuid}', 'FreelancersController@update_milestone');
        Route::post('milestones/freelancer/update_status/{uuid}', 'FreelancersController@update_milestone_status');
        Route::post('milestones/add', 'FreelancersController@add_milestone');
    });

    // Hirer Stuff
    Route::group(['middleware' => ['role:hirer']], function () {
        Route::get('new-jobs', 'HirerController@not_assigned_jobs')->name('new-jobs');
        Route::post('new-invite', 'HirerController@send_invite')->name('new-invite');
        Route::post('review_freelancer/{uuid}', 'HirerController@review_freelancer');
        Route::get('bidders/{uuid}', 'HirerController@manage_bids')->name('bidders');
        Route::post('bidders/accept_bid/{uuid}', 'HirerController@accept_bid');
        Route::post('milestones/release_payment/{uuid}', 'HirerController@release_payment_for_milestone');
        Route::post('milestones/hirer/update_status/{uuid}', 'HirerController@update_milestone');
    });

    Route::get('ongoing-jobs', 'JobsController@ongoing_jobs')->name('ongoing-jobs');

    Route::get('completed-jobs', 'JobsController@completed_jobs_')->name('completed-jobs');
    

    // Both Freelancer and Hirer
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('invites', 'DashboardController@invites')->name('invites');
    Route::resource('bookmarks', 'BookmarksController')->only(['index', 'destroy']);
    Route::post('jobs/bookmarks-toggle-api', 'BookmarksController@toggle_api');
    Route::post('freelancers/bookmarks-toggle-api', 'BookmarksController@toggle_api');
    Route::get('reviews', 'ReviewsController@index')->name('reviews');


    Route::get('user', function (Request $request) {
        return response()->json($request->user()->profile);
    })->middleware('auth');

    Route::get('settings', 'DashboardController@settings')->name('settings');
    Route::get('verify-profile', 'DashboardController@verify');

    Route::get('milestones/{id}', 'DashboardController@milestones')->name('milestones');

    // Subscription
    Route::get('pricing', function () {

        $plans = App\Models\Plan::oldest()->get();
        $my_plan = Auth::user()->plan()->first();
    
        return view('subscription.pricing', compact('plans', 'my_plan'));

    })->name('pricing');

       // Subscription
    Route::get('cancel', function () {

        $plans = App\Models\Plan::oldest()->get();
        $my_plan = Auth::user()->plan()->first();
    
        return view('subscription.cancel', compact('plans', 'my_plan'));

    })->name('cancel');

    Route::get('cancel/subscription', function () {
        $user = Auth::user();
        $plan = App\Models\Plan::where('plan_id', 'free')->first();
        $my_plan = $user->plan()->first();
    
        try {
            if ($user->subscribed('main')) {
                $user->subscription('main')->cancel();
            }
            
        } catch (\Exception $e) {
            toastr()->error('Sorry, We could not cancel subscription, something went wrong, contact support or try again');
            return redirect()->back();
        }
        if($my_plan->title == 'free'){
            toastr()->error('Sorry, We could not cancel subscription, something went wrong, contact support or try again');
            return redirect()->with('error','Free Plan can not be  Cancelled');
        }

        $user->plan()->sync([$plan->id => ['count' => 0]]);

        toastr()->success('Success, Subscription Cancelled');
        return back()->with('success','Subscription Cancelled');

    });

    Route::post('billing/paymentmethod/update', function(Request $request){

        $user = Auth::user();
    
        try {
            if ($user->hasPaymentMethod()) {
                $user->updateDefaultPaymentMethod($request->method);
            }else {
                $user->addPaymentMethod($request->method);
                $user->updateDefaultPaymentMethod($request->method);
            } 
        } catch (\Laravel\Cashier\Exceptions\InvalidStripeCustomer $e) {
            
            $user->createAsStripeCustomer();
            $user->addPaymentMethod($request->method);
            $user->updateDefaultPaymentMethod($request->method);
        }
      
    
        return response()->json([
            'message' => "Payment Method Added Successful",
            'data' => $user->defaultPaymentMethod()
        ]);
    });
    
    Route::get('checkout/{id}', function ($id) {
        $plan = App\Models\Plan::find($id);
    
        $user = Auth::user();
    
        try {
            $card = $user->defaultPaymentMethod()->card;
        } catch (\Throwable $th) {
           $card = NULL;
        }
       
    
        $intent =  $user->createSetupIntent();
    
        return view('subscription.checkout', compact('plan', 'intent', 'card'));
    
    })->name('checkout');
    
    Route::post('order-confirmation', function (Request $request) {
        $validateData = $request->validate([
            'plan' => 'integer|required'
        ]);
        $plan = App\Models\Plan::find($request->plan);
    
        $user = Auth::user();
    
        $paymentMethod = $user->defaultPaymentMethod();
        if(empty($paymentMethod)){
            toastr()->error('Sorry, Add valid card details before subscribing');
            return redirect()->back();
        }
    
        try {
            if ($user->subscribed('main')) {
                $user->subscription('main')->cancel();
            }
            $user->newSubscription('main', $plan->plan_id)->create($paymentMethod->id, [
                'email' => $user->email,
            ]);
            
        } catch (\Exception $e) {
            toastr()->error('Sorry, ' . $e->getMessage());
            return redirect()->back();
        }

        $user->plan()->sync([$plan->id => ['count' => 0]]);
    
        return view('subscription.confirmation', compact('plan'));
    })->name('confirmation');
    
    Route::get('invoice', function () {
    
        $user = Auth::user();
    
        $invoice = $user->invoices()->first();
    
        return Auth::user()->downloadInvoice($invoice->id, [
            'vendor' => 'Yohli',
            'product' => 'Bids',
        ]);
    })->name('invoice');
    
    Route::post('add-funds', 'AccountController@update_account');
    
    // Finances
    Route::get('add-funds', function () {
        $intent = Auth::user()->createSetupIntent();
        $payment_provider = App\Models\PaymentProvider::where('title', 'card')->first();

        $account = \App\Models\Account::where('user_id', Auth::user()->id)->first();
    
        return view('dashboard.finances.add_funds', compact('intent', 'account', 'payment_provider'));
    })->name('add-funds');
    
    Route::get('withdraw-funds', function () {
        $payment_providers = App\Models\PaymentProvider::all();

        $account = \App\Models\Account::where('user_id', Auth::user()->id)->first();

        $jobs = \App\Models\Job::where('status', 'assigned')->where('user_id', $account->user_id)->with(['bids'=> function($q){
                $q->where('status', 'accepted');
                }, 'milestones' => function($q){
                    $q->where('status', 'paid');
                }])->get();

       $bid_amount = 0;

       foreach ($jobs as $job) {
            $bid_sum = $job->bids()->where('status', 'accepted')->first();

            if($bid_sum){
                $bid_sum = $bid_sum->rate;
            }else{
                $bid_sum = 0;
            }


           $bid_amount =  $bid_amount + $bid_sum;
       }

       $milestone_amount = 0;

       foreach ($jobs as $job) {
           $milestone_sum = $job->milestones()->where('status', 'paid')->sum('cost');

           $milestone_amount =  $milestone_amount + $milestone_sum;
       }

        $escrow = $milestone_amount + $bid_amount;

        $profile = Auth::user()->profile;
    
        return view('dashboard.finances.withdraw_funds',  compact('account', 'profile', 'escrow', 'payment_providers'));
    
    })->name('withdraw-funds');
    
    
    Route::get('transactions-history', function () {
        $account = \App\Models\Account::where('user_id', Auth::user()->id)->first();

        $transactions =  \App\Models\Transaction::where('account_id', Auth::user()->account->id)->latest()->limit(10)->get();
    
        return view('dashboard.finances.transactions',  compact('account', 'transactions'));
        
    })->name('transactions-history');

});

Route::get('test', function(Request $request){
    // return response()->json(geoip($request->ip()));

    // Maatwebsite\Excel\Facades\Excel::import(new App\Imports\SkillImport, storage_path('skillset.csv'));

    return response()->json(['success']);

});

Route::get('contact', function(){
    return view('contact');
});

Route::get('terms', function(){
    return view('terms');
});

Route::get('privacy', function(){
    return view('privacy');
});

Route::get('copyright', function(){
    return view('copyright');
});



Route::post(
    'stripe/webhook',
    '\App\Http\Controllers\WebhookController@handleWebhook'
);