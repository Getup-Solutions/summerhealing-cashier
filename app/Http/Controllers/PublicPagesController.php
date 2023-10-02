<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Facility;
use App\Models\Training;
use App\Models\Subscription;
use Illuminate\Http\Request;

class PublicPagesController extends Controller
{
    //
    public function homePage()
    {
        // $homePageContent = HomePageContent::first();
        return Inertia::render('Public/Home', [
            // 'subscription_plans'=>Subscription::all()->take(3),
            'courses'=>Course::select('title','excerpt','description','thumbnail','slug','price')->where('published',1)->get(),
            // 'homePageContent' => $homePageContent,
            // 'categories' => Category::all(),
            // 'productBestSellers'=>Product::whereIn('tag',['best_seller','new_arrival'])->paginate(10)->withQueryString()
        ]);
    }

    public function subscriptionsPage(){

        // dd(Subscription::select('title','description','slug','validity','price')->where('published',1)->get());
        return Inertia::render('Public/Subscriptions', [
            'subscription_plans'=>Subscription::select('title','description','thumbnail','slug','validity','price')->where('published',1)->get(),
            // 'courses'=>Course::all()->take(3),
            // 'homePageContent' => $homePageContent,
            // 'categories' => Category::all(),
            // 'productBestSellers'=>Product::whereIn('tag',['best_seller','new_arrival'])->paginate(10)->withQueryString()
        ]);
    }

    public function coursesPage(){

        // dd(Subscription::select('title','description','slug','validity','price')->where('published',1)->get());
        return Inertia::render('Public/Courses', [
            'courses'=>Course::select('title','excerpt','description','thumbnail','slug','price')->where('published',1)->get(),
            // 'courses'=>Course::all()->take(3),
            // 'homePageContent' => $homePageContent,
            // 'categories' => Category::all(),
            // 'productBestSellers'=>Product::whereIn('tag',['best_seller','new_arrival'])->paginate(10)->withQueryString()
        ]);
    }

    public function facilitiesPage(){
        return Inertia::render('Public/Facilities', [
            'facilities'=>Facility::select('title','excerpt','description','thumbnail','slug','price')->where('published',1)->get(),
            // 'courses'=>Course::all()->take(3),
            // 'homePageContent' => $homePageContent,
            // 'categories' => Category::all(),
            // 'productBestSellers'=>Product::whereIn('tag',['best_seller','new_arrival'])->paginate(10)->withQueryString()
        ]);
    }

    public function trainingsPage(){
        return Inertia::render('Public/Trainings', [
            'trainings'=>Training::select('title','excerpt','description','thumbnail','slug')->where('published',1)->get(),
            // 'courses'=>Course::all()->take(3),
            // 'homePageContent' => $homePageContent,
            // 'categories' => Category::all(),
            // 'productBestSellers'=>Product::whereIn('tag',['best_seller','new_arrival'])->paginate(10)->withQueryString()
        ]);
    }

    public function subscriptionSinglePage(Subscription $subscription){

        $courses_included = $subscription->courses()->get();
        foreach($courses_included as $course){
            if($course->pivot->course_price == 0) {
                $course->offer_price = 'FREE!';
            } elseif($course->pivot->course_price < $course->price) {
                $course->offer_price = $course->pivot->course_price.' (' .round(($course->pivot->course_price*100)/$course->price,2,PHP_ROUND_HALF_UP).'% Discount)';
            } else {
                $course->offer_price = $course->price;
            }
        }

        $facilities_included = $subscription->facilities()->get();
        foreach($facilities_included as $facility){
            if($facility->pivot->facility_price == 0) {
                $facility->offer_price = 'FREE!';
            } elseif($facility->pivot->facility_price < $facility->price) {
                $facility->offer_price = $facility->pivot->facility_price.' (' .round(($facility->pivot->facility_price*100)/$facility->price,2,PHP_ROUND_HALF_UP).'% Discount)';
            } else {
                $facility->offer_price = $facility->price;
            }
        }
        $user_have_subscription = false;
        $logged_user = Auth::guard('web')->user();
        // dd($logged_user->hasSubscription($subscription->id));
        // $user_have_subscription_expires_in
        

        // if(Auth::guard('web')->user()->subscriptions()->get()->find($subscription->id) !== null){
        //     $user_have_subscription = true;
        //     $user_have_subscription_created = Auth::guard('web')->user()->subscriptions()->get()->find($subscription->id)->pivot->created_at;
        //     // dd($user_have_subscription_created);
        //     $now = Carbon::now(); 
        //     $user_have_subscription_expires_in = $user_have_subscription_created->diffInDays($now);
        //     dd($user_have_subscription_created->diffInDays($now));
        //     dd(Auth::guard('web')->user()->subscriptions()->get()->find($subscription->id)->pivot->created_at);
        // }
        return Inertia::render('Public/SubscriptionSingle', [
            'subscription_plan'=>$subscription,
            'user_have_subscription'=>$logged_user ? $logged_user->hasSubscription($subscription->id) : false,
            // 'user_have_subscription_expires_in' => $user_have_subscription_expires_in,
            'courses_included'=>$courses_included,
            'facilities_included'=>$facilities_included
        ]);
    }

    public function courseSinglePage(Course $course){

        $included_subscriptions = $course->subscriptions()->get();
        // dd($included_subscriptions);
        foreach($included_subscriptions as $subscription){
            if($subscription->pivot->course_price == 0) {
                $subscription->offer_price = 'FREE!';
            } elseif($subscription->pivot->course_price < $course->price) {
                $subscription->offer_price = $subscription->pivot->course_price.' (' .round(($subscription->pivot->course_price*100)/$course->price,2,PHP_ROUND_HALF_UP).'% Discount)';
            } else {
                $subscription->offer_price = $course->price;
            }
        }

        // dd($included_subscriptions);
        return Inertia::render('Public/CourseSingle', [
            'course'=>$course,
            'included_subscriptions'=>$included_subscriptions
        ]);
    }

    public function facilitySinglePage(Facility $facility){

        $included_subscriptions = $facility->subscriptions()->get();
        foreach($included_subscriptions as $subscription){
            if($subscription->pivot->facility_price == 0) {
                $subscription->offer_price = 'FREE!';
            } elseif($subscription->pivot->facility_price < $facility->price) {
                $subscription->offer_price = $subscription->pivot->facility_price.' (' .round(($subscription->pivot->facility_price*100)/$facility->price,2,PHP_ROUND_HALF_UP).'% Discount)';
            } else {
                $subscription->offer_price = $facility->price;
            }
        }
        return Inertia::render('Public/FacilitySingle', [
            'facility'=>$facility,
            'included_subscriptions'=>$included_subscriptions
        ]);
    }
}
