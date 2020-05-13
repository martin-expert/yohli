<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\Skill;
use App\Models\Account;
use App\Models\Profile;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\MoMoPayOutEvent;
use App\Models\PaymentProvider;
use Illuminate\Validation\Rule;
use App\Events\PayPalPayOutEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Aggregates\AccountAggregate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\CouldNotSubtractMoney;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $profile_uuid
     * @return \Illuminate\Http\Response
     */
    public function update_password(Request $request)
    {
        $messages = [
            'password.regex' => 'Password must contain at least one number, one symbol and both uppercase and lowercase letters.'
        ];

        Validator::make($request->all(), [
            'current_password' => 'required|string|min:6',
            'password' => 'required|min:6|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).+$/',
        ], $messages)->validate();


        $current_password = Auth::User()->password;
        if (Hash::check($request->current_password, $current_password)) {
            $user = User::find(Auth::id());
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->back()->with('success', 'Password updated successfully');
        } else {

            return redirect()->back()->with('error', 'Current Password was incorrect');
        }
    }

    public function update_role(Request $request){
        $user = User::find(Auth::id()); 
        $profile = Profile::find($user->profile->id);

        if($request->account_type == 'freelancer'){
            if($user->review == 'pending'){
                toastr()->error('Your account is already under review. You can only switch when review is completed and successful');
                return back();
            }
    
            if($user->review == 'not started'){
                toastr()->error('Sorry, could not switch, upload required document to start the verification process');
                return redirect('verify-profile');
            }
    
            if($user->review == 'failed'){
                toastr()->error('Sorry, documents uploaded were not valid. Upload a valid document');
                return redirect('verify-profile');
            }

            if($user->review != 'successful'){
                toastr()->error('Sorry, could not switch, upload required document to start the verification process');
                return redirect('verify-profile');
            }
        }

        if($user->review != 'successful'){
            toastr()->error('Sorry, only verified account can switch, upload required document to start the verification process');
            return redirect('verify-profile');
        }

        $user->syncRoles([$request->account_type]); 
      
        $profile->type = $request->account_type;
        $profile->verified = 1;
        $profile->save();

        session(['role' => $request->account_type]);

        toastr()->success('Your account type has been switched to '. ucfirst($request->account_type));
        \Log::info("worked");
        return back();
    }

    public function verify_profile(){        
        $user = User::find(Auth::id()); 
        $user->review = 'successful';
        $user->profile_verified_at = now();
        $user->save();

        $profile = Profile::find($user->profile->id);
        $profile->verified = 1;
        $profile->save();
    }

    public function update_basic_info(Request $request)
    {

        Validator::make($request->all(), [
            'email' =>  [
                'required', 'email',
                Rule::unique('users', 'email')->ignore(Auth::id()),
            ],
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => ['required', 'numeric', Rule::unique('users', 'phone')->ignore(Auth::id())],
            'country_id' => 'required|numeric',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [])->validate();

        try {
            DB::beginTransaction();

            $user = User::find(Auth::id());
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->save();

            $profile = Profile::find($user->profile->id);
            $profile->name = $user->first_name . " ". $user->last_name;
            $profile->email = $user->email;
            $profile->phone = $user->phone;
            $profile->country_id = $request->country_id;

            $profile->save();

            if ($request->hasFile('picture')) {
                // Check if media already exists
                $media = $profile->getMedia('profile');
                if (sizeof($media) != 0) {
                    // Remove old image
                    $media[0]->delete();
                }
                // Add New Image
                $profile
                    ->addMediaFromRequest('picture')
                    ->toMediaCollection('profile');
            }

            DB::commit();
            return back()->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                "code" => $e->getCode(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ]);

            // Rollback DB transactions is an error occurred
            DB::rollback();

            return back()->with('error', 'Something went wrong')->withInput();
        }
    }

    public function update_freelancer_info(Request $request)
    {
        Validator::make($request->all(), [
            'rate' => 'required',
            'headline' => 'required',
            'description' => 'required',
            'documents.*' => 'file|max:10240',
        ], [])->validate();

        $skills = [];

        if($request->filled('skills')){
            foreach ($request->skills as $index => $skill) {
                if(is_numeric($skill)){
                    array_push($skills, $skill);
                }  else {
                    $skillId = $this->createSkill($skill);
                    array_push($skills, $skillId);
                }
                
            }
        }
        

        try {
            DB::beginTransaction();

            $profile = Profile::find(Auth::user()->profile->id);
            $profile->rate = $request->rate;
            $profile->headline = $request->headline;
            $profile->description = $request->description;
            $profile->skills()->sync($skills);

            $profile->save();


            if ($request->hasFile('documents')) {
                $profile
                    ->addMultipleMediaFromRequest(['documents'])
                    ->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection('cv');
                    });
            }

            DB::commit();
            return back()->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                "code" => $e->getCode(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ]);

            // Rollback DB transactions is an error occurred
            DB::rollback();

            return back()->with('error', 'Something went wrong')->withInput();
        }
    }

    public function update_freelancer_documents(Request $request)
    {
        $messages = [
            'documents.*.max' => 'File must be less than or equal to 2 MB'
        ];
        Validator::make($request->all(), [
            'documents.*' => 'required|file|max:2000',
        ],  $messages)->validate();

        try {
            DB::beginTransaction();

            $profile = Profile::find(Auth::user()->profile->id);

            if ($request->hasFile('documents')) {
                $profile
                    ->addMultipleMediaFromRequest(['documents'])
                    ->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection('cv');
                    });
            }

            $user = User::find(Auth::id());
            $user->review = 'pending';
            $user->save();

            DB::commit();
            return redirect('settings')->with('success', 'Your uploaded documents are now under review.');
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                "code" => $e->getCode(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ]);

            // Rollback DB transactions is an error occurred
            DB::rollback();

            return back()->with('error', 'Something went wrong')->withInput();
        }
    }

    private function createSkill($skillName){
        $skill = new Skill();
        $skill->title = $skillName;
        $skill->save();
        return $skill->id;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $profile_uuid
     * @return \Illuminate\Http\Response
     */
    public function update_profile(Request $request)
    {
        $validateData = $request->validate([
            'profile_name' => 'required|string',
            'profile_email' => 'required|unique:companies,email',
            'profile_phone' => 'required|numeric',
            'address' => 'nullable|string',
            'headline' => 'nullable|string',
            'city' => 'nullable|string',
            'region' => 'nullable|string',
            'country' => 'nullable',
            'rate' => 'nullable|string',
            'skills' => 'nullable',
            'picture' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'documents' => 'nullable|file'
        ]);

        $profile = Profile::where('user_id', Auth::user()->id)->first();
        $profile->name = $request->profile_name;
        $profile->email = $request->profile_email;
        $profile->phone1 = $request->profile_phone;
        $profile->address = $request->address;
        $profile->city = $request->city;
        $profile->region = $request->region;
        $profile->country = $request->country;
        $profile->rate = $request->rate;
        $profile->headline = $request->headline;
        $profile->save();

        $profile->skills()->sync($request->skills);


        if ($request->hasFile('picture')) {
            $profile
                ->addMediaFromRequest('picture') //starting method
                ->toMediaCollection('profile');
        }

        if ($request->hasFile('documents')) {
            $fileAdders = $profile
                ->addMultipleMediaFromRequest('documents')
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('cv');
                });
        }

        return back()->with('status', "profile updated Succesfully");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $profile_uuid
     * @return \Illuminate\Http\Response
     */
    public function update_withdrawal_info(Request $request)
    {
        $validateData = $request->validate([
            'paypal' => 'nullable|string',
            'skrill' => 'nullable|string',
            'momo' => 'nullable|string',
            'momo_country' => 'nullable|string',
            'momo_network' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'bank_account_name' => 'nullable|string',
            'bank_no' => 'nullable|string',
            'bank_branch' => 'nullable|string',
            'bank_country' => 'nullable|string',
            'bank_routing_number' => 'nullable|string',
            'bank_account_type' => 'nullable|string',
        ]);

        $profile = Profile::where('user_id', Auth::user()->id)->first();
        $profile->paypal = $request->paypal;
        $profile->skrill = $request->skrill;
        $profile->momo = $request->momo;
        $profile->momo_country = $request->momo_country;
        $profile->momo_network = $request->momo_network;
        $profile->bank_name = $request->bank_name;
        $profile->bank_account_name = $request->bank_account_name;
        $profile->bank_no = $request->bank_no;
        $profile->bank_branch = $request->bank_branch;
        $profile->bank_country = $request->bank_country;
        $profile->bank_routing_number = $request->bank_routing_number;
        $profile->bank_account_type = $request->bank_account_type;
        $profile->save();

        toastr()->success("Withdrawal Info updated Succesfully");

        return back()->with('status', "Withdrawal Info updated Succesfully");
    }

    

    public function view_account()
    {
        $account = Account::where('user_id', Auth::user()->id)->first();
        return response()->json($account);
    }
    
    public function update_account(Request $request){
        $validateData = $request->validate([
            'amount' => 'required|integer',
            'method' => 'required',
        ]);
    
        $amount = bcmul($request->amount + $request->percentage , 100);
        $user = $request->user();
        if($request->has('deposit')){
            try {
                $payment = $user->charge($amount, $request->method);
            } catch (Exception $e) {
                $payment = new Transaction;
                $payment->amount = $request->amount  + $request->percentage;
                $payment->account_id = $user->account->id;
                $payment->status = "failed";
                $payment->type = $request->type;
                $payment->payment_method = $request->method;
                $payment->description = "Account " . $request->type . " Failed";
                $payment->save();
                if($request->ajax()){
                    return response()->json([
                        'message' => "Account " . $request->type . " Failed",
                        'status' => "Successful"
                    ]);
                }

                toastr()->error("Account " . $request->type . " failed");
                return back();
                
            } 
        } 
        $payment = new Transaction;
        $payment->amount = $request->amount;
        $payment->charge =  $request->percentage;
        $payment->account_id = $user->account->id;
        $payment->type = $request->type;
        $payment->payment_method = $request->method;
        $payment->description = "Account " . $request->type . " Successful";
        
    
        $account = $user->account;
    
        if($account){
            $aggregateRoot = AccountAggregate::retrieve($account->uuid);

            if($request->has('deposit')){
                $payment->status = "success";
                $aggregateRoot->addMoney($request->amount);
               
            }
            
            if($request->has('withdrawal')){

                $jobs = Job::where('status', 'assigned')->where('user_id', $account->user_id)->with(['bids'=> function($q){
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
               
             
                $fee = PaymentProvider::where('title', $request->method)->first();
                $fee = $fee->withdrawal_rate;
                $fee = $fee / 100;
                $fee = $fee * $request->amount;

                $payment->charge =  $fee;

                $withdrawal_amount = $request->amount + $fee;

                $aggregateRoot->setAccountLimit($bid_amount + $milestone_amount);
                       
                try {
                    $payment->status = "pending";
                    $payment->description = "Account " . $request->type . " Pending";
                    $aggregateRoot->subtractMoney($withdrawal_amount);
                } catch (CouldNotSubtractMoney $e) {
                    \Log::error($e->getMessage());

                    if($request->ajax()){
                        return response()->json([
                            'message' => $e->getMessage(),
                            'status' => "Falied"
                        ]);
                    }
        
                    toastr()->error("Account " . $e->getMessage() . " failed");
                    return back();

                }
                
            }

            $aggregateRoot->persist(); 
            $payment->save();

            if($request->method == 'paypal')
            {
                event(new PayPalPayOutEvent($user->profile, $payment));
            }

            if($request->method == 'momo')
            {
                event(new MoMoPayOutEvent($user->profile, $payment));
            }
            
        }else{
            
            if($request->ajax()){
                return response()->json([
                    'message' => "Account " . $request->type . " Failed",
                    'status' => "Successful"
                ]);
            }

            toastr()->error("Account " . $request->type . " failed");
            return back();

        }

       
        
        if($request->ajax()){
            return response()->json([
                'message' => "Account " . $request->type . " Successful",
                'status' => "Successful"
            ]);
        }

        toastr()->success("Account " . $request->type . " Success");
        return back();


    }
    public function delete_account(Account $account)
    {
        AccountAggregate::retrieve($account->uuid)
            ->deleteAccount()
            ->persist();
        return back();
    }
}
