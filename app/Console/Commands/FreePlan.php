<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FreePlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'free:plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Free Plan';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::chunk(200, function($users){
            foreach ($users as $user) {
               if($user->plan()->exists()){
                    if($user->plan->plan_id == 'free'){
                        // $my_plan = Plan::where('plan_id', 'free')->first();
                        $user->plan()->sync([$user->plan->id => ['count' => 0]]);
                    }else{
                        $count = $user->plan->pivot->count - 10;
                        if($count <= 0){
                            $user->plan()->sync([$user->plan->id => ['count' => 0]]);
                        }else{
                            $user->plan()->sync([$user->plan->id => ['count' => $count]]);
                        }
                    }
                
               }else{
                   $user->plan->sync([id => ['count' => 0]]);
               }
            }
        });
    }
}
