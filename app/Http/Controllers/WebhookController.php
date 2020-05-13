<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\Events\WebhookHandled;
use Laravel\Cashier\Events\WebhookReceived;

use Symfony\Component\HttpFoundation\Response;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
      /**
     * Handle a Stripe webhook call.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $method = 'handle'.Str::studly(str_replace('.', '_', $payload['type']));

        WebhookReceived::dispatch($payload);

        if (method_exists($this, $method)) {
            $response = $this->{$method}($payload);

            WebhookHandled::dispatch($payload);

            return $response;
        }

        return $this->missingMethod();
    }

    /**
     * Handle customer subscription updated.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionUpdated(array $payload)
    {
        if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            $data = $payload['data']['object'];

            $user->subscriptions->filter(function (Subscription $subscription) use ($data, $user) {
                return $subscription->stripe_id === $data['id'];
            })->each(function (Subscription $subscription) use ($data, $user) {
                if (isset($data['status']) && $data['status'] === 'incomplete_expired') {
                    $subscription->delete();

                    return;
                }

                // Quantity...
                if (isset($data['quantity'])) {
                    $subscription->quantity = $data['quantity'];
                }

                // Plan...
                if (isset($data['plan']['id'])) {
                    $subscription->stripe_plan = $data['plan']['id']; 
                    try {
                        $my_plan = Plan::where('plan_id', $data['plan']['id'])->first();
                        $user->plan()->sync([$my_plan->id => ['count' => 0]]);
                    } catch (\Exception $e) {
                        \Log::error($e->getMessage());
                    }
                }

                // Trial ending date...
                if (isset($data['trial_end'])) {
                    $trial_ends = Carbon::createFromTimestamp($data['trial_end']);

                    if (! $subscription->trial_ends_at || $subscription->trial_ends_at->ne($trial_ends)) {
                        $subscription->trial_ends_at = $trial_ends;
                    }
                }

                // Cancellation date...
                if (isset($data['cancel_at_period_end'])) {
                    if ($data['cancel_at_period_end']) {
                        $subscription->ends_at = $subscription->onTrial()
                            ? $subscription->trial_ends_at
                            : Carbon::createFromTimestamp($data['current_period_end']);
                    } else {
                        $subscription->ends_at = null;
                    }
                }

                // Status...
                if (isset($data['status'])) {
                    $subscription->stripe_status = $data['status'];
                }

                $subscription->save();
            });
        }

        return $this->successMethod();
    }

    /**
     * Handle a cancelled customer from a Stripe subscription.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            $user->subscriptions->filter(function ($subscription) use ($payload) {
                return $subscription->stripe_id === $payload['data']['object']['id'];
            })->each(function ($subscription) {
                $subscription->markAsCancelled();
            });
            try {
                $my_plan = Plan::where('plan_id', 'free')->first();
                $user->plan()->sync([$my_plan->id => ['count' => 0]]);
            } catch(\Exception $e) {
                \Log::error($e->getMessage());
            }
        }

        return $this->successMethod();
    }

}

