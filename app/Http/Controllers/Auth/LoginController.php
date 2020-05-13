<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/browse-jobs';
    public function redirectTo()
    {
        // if (auth()->user()->hasRole('freelancer')) {
        //     return '/browse-jobs';
        // } else {
        //     return '/browse-freelancers';
        // }
        
        return 'dashboard';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the Provider authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {

        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }
        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if ($existingUser) {
            // log them in
            auth()->login($existingUser, true);
            return redirect()->to('/dashboard');
        } else {
            // create a new user
            $newUser = new User;
            $name = explode(" ", $user->getName());
            if(isset($name[0])){
                $newUser->first_name = $name[0];
            }
            if(isset($name[1])){
                $$newUser->last_name = $name[1];
            }
            if(isset($name[2])){
                $$newUser->last_name = $name[1]. " " .$name[2];
            }
            if(isset($name[3])){
                $$newUser->last_name = $name[1]. " " .$name[2] . " " .$name[3];
            }
           
            // $newUser->id = $user->getId();
            $newUser->email = $user->getEmail();
            $newUser->avatar =  $user->getAvatar();
            $newUser->save();
            auth()->login($newUser, true);

            $newUser->profile()->create([
                'name' => $user->getName(),
                'email' => $user->getName(),
            ]);

            return redirect()->to('/update_profile');
    
        }
        
    }
}
