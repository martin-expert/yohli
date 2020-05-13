<?php

/**
* returns a 13 digit code based on timestamp
*
* @return int
*/
function unique_code()
{
    $milliseconds = (String) round(microtime(true) * 568);
    $shuffled = str_shuffle($milliseconds);
    $id = substr($shuffled, 0, 13);
    return (String) $id;
}


function get_user_plan($userID = false)
{
	if($userID){
		$user = \App\Models\User::where('id', $userID)->first();
		$my_plan = $user->plan()->first();
	}
	else
		$my_plan = Auth::user()->plan()->first();
	$badge = '';		// Default to 'free'
	/*$badge = '<img src="'.asset('assets/images/bronze.png').'" alt="" class="badge-icon" />';*/
	if($my_plan)
		switch($my_plan->plan_id):
			// case 'economy-plus':
			// 	$badge = '<img src="'.asset('assets/images/bronze.png').'" alt="" class="badge-icon"/>';
			// 	break;
			case 'business':
				$badge = '<img src="'.asset('assets/images/silver-white.png').'" alt="" class="badge-icon silver-badge"/>';
				break;
			case 'first-class':
				$badge = '<img src="'.asset('assets/images/silver-white.png').'" alt="" class="badge-icon gold-badge"/>';
				break;
		endswitch;
	return ['badge' => $badge, 'plan' => $my_plan->plan_id];
}