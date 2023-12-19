<?php

namespace App\Http\Controllers;

use App\Models\Subscriptionplan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Session;
use App\Models\Facility;
use App\Services\PayUService\Exception;
use Stripe\PaymentIntent;

// use Stripe;

class SubscriptionCheckoutController extends Controller
{
    protected $stripe;
    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(config('stripe.secret_key'));
    }

    public function checkoutCreate(Subscriptionplan $subscriptionplan)
    {
        // $courses_included = $subscriptionplan->courses()->get();
        // foreach ($courses_included as $course) {
        //     if ($course->pivot->course_price == 0) {
        //         $course->offer_price = 'FREE!';
        //     } elseif ($course->pivot->course_price < $course->price) {
        //         $course->offer_price = $course->pivot->course_price . ' (' . round(($course->pivot->course_price * 100) / $course->price, 2, PHP_ROUND_HALF_UP) . '% Discount)';
        //     } else {
        //         $course->offer_price = $course->price;
        //     }
        // }

        // $facilities_included = $subscriptionplan->facilities()->get();
        // foreach ($facilities_included as $facility) {
        //     if ($facility->pivot->facility_price == 0) {
        //         $facility->offer_price = 'FREE!';
        //     } elseif ($facility->pivot->facility_price < $facility->price) {
        //         $facility->offer_price = $facility->pivot->facility_price . ' (' . round(($facility->pivot->facility_price * 100) / $facility->price, 2, PHP_ROUND_HALF_UP) . '% Discount)';
        //     } else {
        //         $facility->offer_price = $facility->price;
        //     }
        // }
        $user_have_subscriptionplan = false;
        $logged_user = Auth::guard('web')->user();

        $sessions_credits = $subscriptionplan->credits()->where('creditable_type', 'App\Models\Session')->where('creditable_id', '!=', 0)->get();
        foreach ($sessions_credits as $session_credit) {
            $session_credit["title"] = Session::find($session_credit->creditable_id)->title;
        }
        $facility_credits = $subscriptionplan->credits()->where('creditable_type', 'App\Models\Facility')->where('creditable_id', '!=', 0)->get();
        foreach ($facility_credits as $facility_credit) {
            $facility_credit["title"] = Facility::find($facility_credit->creditable_id)->title;
        }

        return Inertia::render('Public/SubscriptionplanCheckout', [
            'subscriptionplan' => $subscriptionplan,
            'sessions_credits' => $sessions_credits,
            'facilities_credits' => $facility_credits,
            'intent' => Auth::user()->createSetupIntent(),
            'stripePublicKey' => config('stripe.public_key'),
            'user_have_subscriptionplan' => $logged_user ? $logged_user->subscribed($subscriptionplan['slug']) : false,
            // 'user_have_subscriptionplan_expires_in' => $user_have_subscriptionplan_expires_in,
            // 'courses_included' => $courses_included,
            // 'facilities_included' => $facilities_included
        ]);
    }

    public function checkoutPost(Request $request)
    {
        // dd('dd');
        $user = Auth::user();
        $plan = $request->get('plan');
        // $stripe_price = $this->stripe->plans->retrieve(
        //     $plan["plan_id"],
        //     []
        //   );
        // dd($stripe_price);
        // dd($plan["price"]);
        if ($user->subscribed($plan['slug'])) {
            return back()->with('error', 'You already have this Membership');
        } else if ($user->subscriptions->count() > 0) {
            return back()->with('error', 'You already have a valid Membership');
        } else {
            try {
                $paymentMethodID = $request->get('payment_method');
                if ($user->stripe_id == null) {
                    $user->createOrGetStripeCustomer(['name' => $user->full_name, 'phone' => $user->phone_number, 'email' => $user->email]);
                }
                $user->addPaymentMethod($paymentMethodID);
                $user->updateDefaultPaymentMethod($paymentMethodID);
                if ($plan["payment_mode"] === 'one-time') {
                    $request->user()->charge(
                        $plan["price"]*100,
                        $paymentMethodID
                    );
                } else {
                    $user->newSubscription($plan["slug"], $plan["plan_id"])
                        ->create($paymentMethodID);
                }

                return redirect('/')->with('success', 'You are subscribed to the ' . $plan["title"]);
            } catch (\Exception $e) {
                // dd('dd');
                return back()->with('error', 'Some error occured - ' . $e->getMessage());
            }
        }
    }
}
