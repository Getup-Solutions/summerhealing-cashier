<?php

namespace App\Http\Controllers;
use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Facility;
use App\Models\Lead;
use App\Models\Session;
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
            'subscriptionplans'=>Subscriptionplan::select('title','description','thumbnail','slug','validity','price')->where('published',1)->latest()->get(),
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


    public function sessionsPage(){

        // dd(Subscriptionplan::select('title','description','slug','validity','price')->where('published',1)->get());
        return Inertia::render('Public/Sessions', [
            'sessions'=>Session::select('title','excerpt','description','thumbnail','slug','price')->where('published',1)->get(),
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

    public function subscriptionplanSinglePage(Subscriptionplan $subscriptionplan){

        // $classes_included = $subscriptionplan->credits()->where('credita')->get();
        // foreach($courses_included as $course){
        //     if($course->pivot->course_price == 0) {
        //         $course->offer_price = 'FREE!';
        //     } elseif($course->pivot->course_price < $course->price) {
        //         $course->offer_price = $course->pivot->course_price.' (' .round(( ($course->price - $course->pivot->course_price)*100)/$course->price,2,PHP_ROUND_HALF_UP).'% Discount)';
        //     } else {
        //         $course->offer_price = $course->price;
        //     }
        // }

        $facilities_included = $subscriptionplan->facilities()->get();
        foreach($facilities_included as $facility){
            if($facility->pivot->facility_price == 0) {
                $facility->offer_price = 'FREE!';
            } elseif($facility->pivot->facility_price < $facility->price) {
                $facility->offer_price = $facility->pivot->facility_price.' (' .round(( ($facility->price - $facility->pivot->facility_price)*100)/$facility->price,2,PHP_ROUND_HALF_UP).'% Discount)';
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
        $sessions_credits = $subscriptionplan->credits()->where('creditable_type','App\Models\Session')->where('creditable_id','!=',0)->get();
        foreach ($sessions_credits as $session_credit) {
            $session_credit["title"] = Session::find($session_credit->creditable_id)->title;
        }
        $facility_credits = $subscriptionplan->credits()->where('creditable_type','App\Models\Facility')->where('creditable_id','!=',0)->get();
        foreach ($facility_credits as $facility_credit) {
            $facility_credit["title"] = Facility::find($facility_credit->creditable_id)->title;
        }
        // dd($sessions_credits);
        return Inertia::render('Public/SubscriptionplanSingle', [
            'subscriptionplan'=>$subscriptionplan,
            'sessions_credits'=>$sessions_credits,
            'facilities_credits'=>$facility_credits,
            'user_have_subscriptionplan'=>$logged_user ? $logged_user->subscribed($subscriptionplan->slug) : false,
            // 'user_have_subscriptionplan_expires_in' => $user_have_subscriptionplan_expires_in,
            // 'courses_included'=>$courses_included,
            // 'facilities_included'=>$facilities_included
        ]);
    }

    public function courseSinglePage(Course $course){

        $included_subscriptionplans = $course->subscriptionplans()->get();
        // dd($included_subscriptionplans);
        foreach($included_subscriptionplans as $subscriptionplan){
            if($subscriptionplan->pivot->course_price == 0) {
                $subscriptionplan->offer_price = 'FREE!';
            } elseif($subscriptionplan->pivot->course_price < $course->price) {
                $subscriptionplan->offer_price = $subscriptionplan->pivot->course_price.' (' .round(( ($course->price - $subscriptionplan->pivot->course_price)*100)/$course->price,2,PHP_ROUND_HALF_UP).'% Discount)';
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

    public function sessionSinglePage(Session $session){

        $included_subscriptionplans = $session->subscriptionplans()->get();
        // dd($included_subscriptionplans);
        foreach($included_subscriptionplans as $subscriptionplan){
            if($subscriptionplan->pivot->session_price == 0) {
                $subscriptionplan->offer_price = 'FREE!';
            } elseif($subscriptionplan->pivot->session_price < $session->price) {
                $subscriptionplan->offer_price = $subscriptionplan->pivot->session_price.' (' .round(( ($session->price - $subscriptionplan->pivot->session_price)*100)/$session->price,2,PHP_ROUND_HALF_UP).'% Discount)';
            } else {
                $subscriptionplan->offer_price = $session->price;
            }
        }

        // dd($included_subscriptionplans);
        return Inertia::render('Public/SessionSingle', [
            'session'=>$session,
            'included_subscriptionplans'=>$included_subscriptionplans
        ]);
    }

    public function facilitySinglePage(Facility $facility){

        $included_subscriptionplans = $facility->subscriptionplans()->get();
        foreach($included_subscriptionplans as $subscriptionplan){
            if($subscriptionplan->pivot->facility_price == 0) {
                $subscriptionplan->offer_price = 'FREE!';
            } elseif($subscriptionplan->pivot->facility_price < $facility->price) {
                $subscriptionplan->offer_price =$subscriptionplan->pivot->facility_price.' (' .round(( ($facility->price - $subscriptionplan->pivot->facility_price)*100)/$facility->price,2,PHP_ROUND_HALF_UP).'% Discount)';
            } else {
                $subscriptionplan->offer_price = $facility->price;
            }
        }
        return Inertia::render('Public/FacilitySingle', [
            'facility'=>$facility,
            'included_subscriptionplans'=>$included_subscriptionplans
        ]);
    }

    public function aboutPage(){
        // dd("sss");
        return Inertia::render('Public/About');
    }

    public function contactPage(){
        // dd("sss");
        return Inertia::render('Public/Contact');
    }

    public function contactPageSubmit(){
        // dd('dd');
        $attributes = request()->validate(
            [
                'first_name' => 'required|min:3|max:50',
                'last_name' => 'required|max:50',
                'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'email' => ['required', 'email'],
                'message'=>'nullable'
            ],
            [
                'phone_number' => 'Enter a valid Phone number with country code',
            ]
        );
        $attributes['source']='contact_page';
        // dd($attributes);
        Lead::create($attributes);
        return back()->with('success', 'Your request has been submitted!');
    }

    public function thankYouPage(){
        return Inertia::render('Public/ThankYou');
    }

}
