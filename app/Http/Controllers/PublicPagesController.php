<?php

namespace App\Http\Controllers;
use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Facility;
use App\Models\Training;
use App\Models\Subscriptionplan;
use Illuminate\Http\Request;

class PublicPagesController extends Controller
{
    //
    public function homePage()
    {
        // $homePageContent = HomePageContent::first();
        return Inertia::render('Public/Home', [
            'subscriptionplans'=>Subscriptionplan::where('published',1)->get()->take(3),
            'courses'=>Course::select('title','excerpt','description','thumbnail','slug','price')->where('published',1)->get(),
            // 'homePageContent' => $homePageContent,
            // 'categories' => Category::all(),
            // 'productBestSellers'=>Product::whereIn('tag',['best_seller','new_arrival'])->paginate(10)->withQueryString()
        ]);
    }

    public function subscriptionplansPage(){

        // dd(Subscriptionplan::select('title','description','slug','validity','price')->where('published',1)->get());
        return Inertia::render('Public/Subscriptionplans', [
            'subscriptionplans'=>Subscriptionplan::select('title','description','thumbnail','slug','validity','price')->where('published',1)->get(),
            // 'courses'=>Course::all()->take(3),
            // 'homePageContent' => $homePageContent,
            // 'categories' => Category::all(),
            // 'productBestSellers'=>Product::whereIn('tag',['best_seller','new_arrival'])->paginate(10)->withQueryString()
        ]);
    }

    public function coursesPage(){

        // dd(Subscriptionplan::select('title','description','slug','validity','price')->where('published',1)->get());
        return Inertia::render('Public/Courses', [
            'courses'=>Course::select('title','excerpt','description','thumbnail','slug','price')->where('published',1)->get(),
            // 'courses'=>Course::all()->take(3),
            // 'homePageContent' => $homePageContent,
            // 'categories' => Category::all(),
            // 'productBestSellers'=>Product::whereIn('tag',['best_seller','new_arrival'])->paginate(10)->withQueryString()
        ]);
    }


    public function calendarPage(){
        // dd(Calendar::whereDate('formated_date', '<=', date("Y/m/d",strtotime("+ 1 month")))->get());
        return Inertia::render('Public/Calendar/Calendar', [
            'calendar'=>Calendar::whereDate('formated_date', '<=', date("Y/m/d",strtotime("+ 1 month")))->get()
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

    public function subscriptionplanSinglePage(Subscriptionplan $subscriptionplan){

        $courses_included = $subscriptionplan->courses()->get();
        foreach($courses_included as $course){
            if($course->pivot->course_price == 0) {
                $course->offer_price = 'FREE!';
            } elseif($course->pivot->course_price < $course->price) {
                $course->offer_price = $course->pivot->course_price.' (' .round(($course->pivot->course_price*100)/$course->price,2,PHP_ROUND_HALF_UP).'% Discount)';
            } else {
                $course->offer_price = $course->price;
            }
        }

        $facilities_included = $subscriptionplan->facilities()->get();
        foreach($facilities_included as $facility){
            if($facility->pivot->facility_price == 0) {
                $facility->offer_price = 'FREE!';
            } elseif($facility->pivot->facility_price < $facility->price) {
                $facility->offer_price = $facility->pivot->facility_price.' (' .round(($facility->pivot->facility_price*100)/$facility->price,2,PHP_ROUND_HALF_UP).'% Discount)';
            } else {
                $facility->offer_price = $facility->price;
            }
        }
        $user_have_subscriptionplan = false;
        $logged_user = Auth::guard('web')->user();
        // dd($logged_user->hasSubscriptionplan($subscriptionplan->id));
        // $user_have_subscriptionplan_expires_in
        

        // if(Auth::guard('web')->user()->subscriptionplans()->get()->find($subscriptionplan->id) !== null){
        //     $user_have_subscriptionplan = true;
        //     $user_have_subscriptionplan_created = Auth::guard('web')->user()->subscriptionplans()->get()->find($subscriptionplan->id)->pivot->created_at;
        //     // dd($user_have_subscriptionplan_created);
        //     $now = Carbon::now(); 
        //     $user_have_subscriptionplan_expires_in = $user_have_subscriptionplan_created->diffInDays($now);
        //     dd($user_have_subscriptionplan_created->diffInDays($now));
        //     dd(Auth::guard('web')->user()->subscriptionplans()->get()->find($subscriptionplan->id)->pivot->created_at);
        // }
        return Inertia::render('Public/SubscriptionplanSingle', [
            'subscriptionplan'=>$subscriptionplan,
            'user_have_subscriptionplan'=>$logged_user ? $logged_user->hasSubscriptionplan($subscriptionplan->id) : false,
            // 'user_have_subscriptionplan_expires_in' => $user_have_subscriptionplan_expires_in,
            'courses_included'=>$courses_included,
            'facilities_included'=>$facilities_included
        ]);
    }

    public function courseSinglePage(Course $course){

        $included_subscriptionplans = $course->subscriptionplans()->get();
        // dd($included_subscriptionplans);
        foreach($included_subscriptionplans as $subscriptionplan){
            if($subscriptionplan->pivot->course_price == 0) {
                $subscriptionplan->offer_price = 'FREE!';
            } elseif($subscriptionplan->pivot->course_price < $course->price) {
                $subscriptionplan->offer_price = $subscriptionplan->pivot->course_price.' (' .round(($subscriptionplan->pivot->course_price*100)/$course->price,2,PHP_ROUND_HALF_UP).'% Discount)';
            } else {
                $subscriptionplan->offer_price = $course->price;
            }
        }

        // dd($included_subscriptionplans);
        return Inertia::render('Public/CourseSingle', [
            'course'=>$course,
            'included_subscriptionplans'=>$included_subscriptionplans
        ]);
    }

    public function facilitySinglePage(Facility $facility){

        $included_subscriptionplans = $facility->subscriptionplans()->get();
        foreach($included_subscriptionplans as $subscriptionplan){
            if($subscriptionplan->pivot->facility_price == 0) {
                $subscriptionplan->offer_price = 'FREE!';
            } elseif($subscriptionplan->pivot->facility_price < $facility->price) {
                $subscriptionplan->offer_price = $subscriptionplan->pivot->facility_price.' (' .round(($subscriptionplan->pivot->facility_price*100)/$facility->price,2,PHP_ROUND_HALF_UP).'% Discount)';
            } else {
                $subscriptionplan->offer_price = $facility->price;
            }
        }
        return Inertia::render('Public/FacilitySingle', [
            'facility'=>$facility,
            'included_subscriptionplans'=>$included_subscriptionplans
        ]);
    }
}
