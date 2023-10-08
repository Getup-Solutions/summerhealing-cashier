<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {

        $publicMenu = json_encode([
            'logo' => [
                "name" => config('app.name'),
                "imageSrc" => config('app.logo'),
                "href" => asset(''),
            ],
            // 'subscriptions'=> [
            //   [
            //     "id"=> "subscription",
            //     "name"=> "Explore",
            //     "featured"=> [
            //       [
            //         "name"=> "Best Seller",
            //         "href"=> asset('').'shop?tag=%5B"best_seller"%5D',
            //         "imageSrc"=>
            //           "https://images.pexels.com/photos/5650017/pexels-photo-5650017.jpeg?auto=compress"
            //       ],
            //       [
            //         "name"=> "New Arrivals",
            //         "href"=> asset('').'shop?tag=%5B"new_arrival"%5D',
            //         "imageSrc"=>
            //           "https://tailwindui.com/img/ecommerce-images/mega-menu-category-01.jpg"
            //       ],

            //     ],
            //     "sections"=> [
            //       [
            //         "id"=> "subscriptions",
            //         "name"=> "Subscription Plans",
            //         "items"=> Subscription::all()
            //       ],
            //       [
            //         "id"=> "courses",
            //         "name"=> "Courses",
            //         "items"=> Course::all()
            //       ],
            //       [
            //         "id"=> "facilities",
            //         "name"=> "Facilities",
            //         "items"=> Facility::all()
            //       ],
            //     ],
            // ],
            // ],
            "pages" => [
                ["name" => "Subscription plans", "href" => asset('') . 'subscriptionplans'],
                ["name" => "Courses", "href" => asset('') . 'courses'],
                ["name" => "Wellness center", "href" => asset('') . 'wellness-center'],
                ["name" => "Training Program", "href" => asset('') . 'trainings'],
                ["name" => "About", "href" => asset('') . 'about'],
                ["name" => "Contact", "href" => asset('') . 'contact'],
            ],
        ]);

        $footerContent = ['page_links' => json_encode([
            [
                'pageName' => 'About',
                'pageLink' => asset('about'),
            ],
            [
                'pageName' => 'Contact',
                'pageLink' => asset('contact'),
            ],
        ]),

            'social_links' => json_encode([
                [
                    'socialName' => 'facebook',
                    'socialLink' => '#',
                ],
                [
                    'socialName' => 'instagram',
                    'socialLink' => '#',
                ],
                [
                    'socialName' => 'youtube',
                    'socialLink' => '#',
                ],
                [
                    'socialName' => 'whatsapp',
                    'socialLink' => '#',
                ],
            ])];

        if (Auth::check()) {
            \Cart::session(Auth::user()->id);
        }

        $shareData = array(
            'csrf_token' => csrf_token(),
            'app_url' => asset('/'),
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
            ],
            'siteLogo' => config('app.logo'),
            'siteName' => config('app.name'),
            'is_user_logged' => Auth::guard('web')->check() ?? false,
            'is_admin_logged' => Auth::guard('web')->check() ? Auth::guard('web')->user()->can('admin') : false,
            'logged_user' => [
                'id' => Auth::guard('web')->check() ? Auth::guard('web')->user()->id : false,
                'email' => Auth::guard('web')->check() ? Auth::guard('web')->user()->email : false,
                'full_name' => Auth::guard('web')->check() ? Auth::guard('web')->user()->full_name : false,
                'first_name' => Auth::guard('web')->check() ? Auth::guard('web')->user()->first_name : false,
                'avatar' => Auth::guard('web')->check() ? Auth::guard('web')->user()->avatar_url : false]);

        if (str_starts_with($request->route()->getName(), 'public')) {
            $shareData = array_merge($shareData, array(
                'mainMenu' => $publicMenu,
                // 'banner_text' => SiteIdentity::first()->banner_text,
                'footerContent' => $footerContent,
                'cartCount' => \Cart::getTotalQuantity(),
                'cartContent' => \Cart::getContent(),
                'cartTotal' => round(\Cart::getTotal(), 2),
            ));
        };

        return array_merge(parent::share($request), $shareData);

        // return array_merge(parent::share($request), [
        //     'csrf_token' => csrf_token(),
        //     'app_url' => asset('/'),
        //     'flash' => [
        //         'success' => fn() => $request->session()->get('success'),
        //         'error' => fn() => $request->session()->get('error'),
        //     ],
        //     'siteLogo' => config('app.logo'),
        //     'siteName' => config('app.name'),
        //     'is_user_logged' => Auth::guard('web')->check() ?? false,
        //     'is_admin_logged' => Auth::guard('web')->check() ? Auth::guard('web')->user()->can('admin') : false,
        //     'logged_user' => [
        //         'id' => Auth::guard('web')->check() ? Auth::guard('web')->user()->id : false,
        //         'email' => Auth::guard('web')->check() ? Auth::guard('web')->user()->email : false,
        //         'full_name' => Auth::guard('web')->check() ? Auth::guard('web')->user()->full_name : false,
        //         'avatar' => Auth::guard('web')->check() ? Auth::guard('web')->user()->avatar_url : false,
        //     ],
        // ]);
    }
}
