<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PublicPagesController;
use App\Http\Controllers\SubscriptionCheckoutController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\TrainerController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\CreditController;
use App\Http\Controllers\Admin\SubscriptionplanController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\CalendarController;
use App\Mail\UserRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PublicPagesController::class,'home']);

Route::name('public.')->group(function () {
    Route::get('/mail',function(){
        Mail::to(Auth::user())->send(new UserRegistration(Auth::user()));
    });
    Route::get('/', [PublicPagesController::class, 'homePage'])->name('home');
    Route::get('/subscriptionplans', [PublicPagesController::class, 'subscriptionplansPage'])->name('subscriptionplans');
    Route::get('/courses', [PublicPagesController::class, 'coursesPage'])->name('courses');
    Route::get('/wellness-center', [PublicPagesController::class, 'facilitiesPage'])->name('facilities');
    Route::get('/trainings', [PublicPagesController::class, 'trainingsPage'])->name('trainings');
    // Route::get('/subscriptionplans', [PublicPagesController::class, 'subscriptionplanPage'])->name('subscriptionplan');
    Route::get('/about', [PublicPagesController::class, 'aboutPage'])->name('about');
    Route::get('/contact', [PublicPagesController::class, 'contactPage'])->name('contact');
    Route::get('/subscriptionplans/{subscriptionplan:slug}', [PublicPagesController::class, 'subscriptionplanSinglePage'])->name('subscriptionplan_single');
    Route::get('/courses/{course:slug}', [PublicPagesController::class, 'courseSinglePage'])->name('course_single');
    Route::get('/wellness-center/{facility:slug}', [PublicPagesController::class, 'facilitySinglePage'])->name('facility_single');
    Route::get('/calendar', [CalendarController::class, 'calendarPage'])->name('calendar.calendar_page');
    Route::get('/calendar/book-event', [CalendarController::class, 'bookEventPage'])->name('calendar.book_event.create');
    Route::get('/calendar/attendance-event', [CalendarController::class, 'attendanceEventPage'])->name('calendar.attendance_event.create');
    Route::post('/calendar/book-event', [CalendarController::class, 'bookEventStore'])->name('calendar.book_event.store');
    Route::post('/calendar/attendance-event', [CalendarController::class, 'attendanceEventStore'])->name('calendar.attendance_event.store');
    Route::delete('/calendar/book-event/{booking:id}', [CalendarController::class, 'bookEventDestroy'])->name('calendar.book_event.destroy');
    Route::delete('/calendar/attendance-event/{attendance:id}', [CalendarController::class, 'attendanceEventDestroy'])->name('calendar.attendance_event.destroy');
    
    Route::name('account.')->group(function(){
        Route::get('login', [LoginController::class, 'login'])->middleware('guest')->name('login');
        Route::post('login', [LoginController::class, 'auth'])->middleware('guest');
        Route::post('logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

        Route::get('register', [LoginController::class, 'create'])->middleware('guest')->name('register');
        Route::post('register', [LoginController::class, 'store'])->middleware('guest');
    });

    Route::name('products.')->group(function(){
        Route::get('/shop', [ProductController::class, 'index'])->name('index');
        Route::get('products/{product:slug}', [ProductController::class, 'show'])->name('show');
    });

    Route::name('cart.')->group(function(){
        // Route::get('cart/', [CartController::class, 'index'])->name('index');
        Route::post('cart/add', [CartController::class, 'add'])->name('add');
        Route::post('cart/update', [CartController::class, 'update'])->name('update');
        Route::post('cart/remove', [CartController::class, 'remove'])->name('remove');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/subscription-plan/checkout/{subscriptionplan:id}', [SubscriptionCheckoutController::class, 'checkoutCreate'])->name('subscription_plan.checkout.create');
        Route::post('/subscription-plan/checkout', [SubscriptionCheckoutController::class, 'checkoutPost'])->name('subscription_plan.checkout.post');
        Route::name('dashboard.')->group(function(){
            Route::prefix('dashboard')->group(function(){
                Route::get('/', [CustomerDashboardController::class, 'index'])->name('home');
                Route::get('/setup-intent', [UserController::class, 'setupIntent'])->name('setup_intent');
                Route::post('/setup-intent', [UserController::class, 'setupIntent'])->name('store.setup_intent');
                Route::get('/address', [CustomerDashboardController::class, 'address'])->name('address');
                Route::get('/orders', [CustomerDashboardController::class, 'orders'])->name('orders');
                Route::post('/{user}', [CustomerDashboardController::class, 'update']);
            });
        });
        Route::name('checkout.')->group(function(){
            Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
            Route::post('checkout', [CheckoutController::class, 'checkout']);
            Route::post('checkout/webhook', [CheckoutController::class, 'webhook']);
            Route::get('checkout/success', [CheckoutController::class, 'success'])->name('success');
            Route::get('checkout/cancel', [CheckoutController::class, 'cancel'])->name('cancel');
        });
    });
});

Route::name('admin.')->group(function () {
    Route::prefix('/admin')->group(function(){
        Route::name('auth.')->group(function () {
            Route::get('/login',[AuthController::class,'login'])->middleware('guest')->name('login');
            Route::post('/login',[AuthController::class,'auth'])->middleware('guest');
            Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth', 'role:ADMIN_ROLE'])->name('logout');
        });


        Route::name('dashboard.')->group(function () {
            Route::middleware(['auth', 'role:ADMIN_ROLE'])->group(function () {
                Route::prefix('/dashboard')->group(function () {
                    Route::get('/', [AdminDashboardController::class, 'home'])->name('home');
                    Route::resource('/users', UserController::class)->except('show');
                    Route::resource('/courses', CourseController::class)->except('show');
                    Route::resource('/facilities', FacilityController::class)->except('show');
                    Route::resource('/sessions', SessionController::class)->except('show');
                    Route::resource('/trainers', TrainerController::class)->except(['show','store']);
                    Route::resource('/subscriptionplans', SubscriptionplanController::class)->except('show');
                    Route::resource('/leads', LeadController::class)->except('show');
                    Route::resource('/trainings', TrainingController::class)->except('show');
                    Route::resource('/credits', CreditController::class)->except('show');

                    // Route::resource('/users', UserController::class)->except('show');
                    // Route::get('/profile-info', [AdminDashboardController::class, 'profileInfo'])->name('profile_info');
                    // Route::put('/profile-info', [AdminDashboardController::class, 'update'])->name('profile_info_update');
                });
            });
        });
    });
    // Route::middleware(['auth', 'role:ADMIN_ROLE'])->group(function () {
    //     Route::prefix('/admin-dashboard')->group(function () {
    //         Route::get('/', [AdminDashboardController::class, 'home'])->name('home');
    //         Route::resource('/users', UserController::class)->except('show');
    //         Route::get('/profile-info', [AdminDashboardController::class, 'profileInfo'])->name('profile_info');
    //         Route::put('/profile-info', [AdminDashboardController::class, 'update'])->name('profile_info_update');
    //     });
    // });
});


