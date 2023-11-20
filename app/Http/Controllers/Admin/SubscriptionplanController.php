<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\Facility;
use App\Models\Session;
use App\Models\Subscriptionplan;
use App\Services\FileManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SubscriptionplanController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(config('stripe.secret_key'));
    }

    public function index()
    {
        return Inertia::render('Admin/Dashboard/Subscriptionplans/Index', [
            'subscriptionplans' => Subscriptionplan::filter(
                request(['search', 'dateStart', 'dateEnd', 'sortBy', 'published'])
            )
                ->paginate(3)->withQueryString(),
            'filters' => Request::only(['search', 'sortBy', 'dateStart', 'dateEnd', 'published']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Dashboard/Subscriptionplans/Create', [
            'sessions' => Session::all(),
            'facilities' => Facility::all(),
        ]);
    }

    public function store(FileManagement $fileManagement)
    {
        $attributes = $this->validateSubscriptionplan();

        if ($attributes['thumbnail'] ?? false) {
            $thumbnail = $attributes['thumbnail'];
        }

        if ($attributes['creditsInfo'] ?? false) {
            $creditsInfo = $attributes['creditsInfo'];
        }
        unset($attributes['creditsInfo']);

        $subscriptionplan = Subscriptionplan::create($attributes);

        if ($subscriptionplan->payment_mode === 'one-time') {
            $stripePlan = $this->stripe->prices->create([
                'unit_amount' => $attributes["price"] * 100,
                'currency' => 'aud',
                'active' => $attributes['published'] == 1,
                'product_data' => ['name' => $attributes['title'], 'metadata' => ['description' => $attributes['description'], 'thumbnail_url' => $subscriptionplan->thumbnail_url], 'unit_label' => $subscriptionplan->id],
            ]);
        } elseif ($subscriptionplan->payment_mode === 'recurring') {
            $stripePlan = $this->stripe->plans->create([
                'amount' => $attributes["price"] * 100,
                'currency' => 'aud',
                'interval' => $attributes["payment_interval"],
                'active' => $attributes["published"] == 1,
                "interval_count" => $attributes["payment_interval_count"],
                'product' => ['name' => $attributes['title'], 'metadata' => ['description' => $attributes['description'], 'thumbnail_url' => $subscriptionplan->thumbnail_url], 'unit_label' => $subscriptionplan->id],
            ]);
        }

        $subscriptionplan->plan_id = $stripePlan->id;

        if ($thumbnail ?? false) {
            $thumbnail = $fileManagement->uploadFile(
                file: $thumbnail,
                path: 'assets/app/images/subscriptionplans/id_' . $subscriptionplan['id'] . '/thumbnail'
            );
            $subscriptionplan->thumbnail = $thumbnail;
            $subscriptionplan->save();
        }

        if ($creditsInfo ?? false) {
            (new CreditController)->store($creditsInfo, $subscriptionplan->id);
        }

        if (Auth::guard('web')->check()) {
            if (Auth::user()->can('admin')) {
                return redirect('/admin/dashboard/subscriptionplans')->with('success', 'Membership plan has been created.');
            }
            return;
        }
        return;
    }

    public function edit(Subscriptionplan $subscriptionplan)
    {
        return Inertia::render('Admin/Dashboard/Subscriptionplans/Edit', [
            'sessions' => Session::all(),
            'facilities' => Facility::all(),
            'subscriptionplan' => $subscriptionplan,
            'sessionGenCredits' => $subscriptionplan->credits()->where('creditable_id', 0)->where('creditable_type', 'App\\Models\\Session')->first(),
            'facilityGenCredits' => $subscriptionplan->credits()->where('creditable_id', 0)->where('creditable_type', 'App\\Models\\Facility')->first(),
            'sessionCredits' => $subscriptionplan->credits()->where('creditable_id', '!=', 0)->where('creditable_type', 'App\\Models\\Session')->get(),
            'facilityCredits' => $subscriptionplan->credits()->where('creditable_id', '!=', 0)->where('creditable_type', 'App\\Models\\Facility')->get(),

        ]);
    }

    public function update(Subscriptionplan $subscriptionplan, FileManagement $fileManagement)
    {

        $attributes = $this->validateSubscriptionplan($subscriptionplan);

        if ($subscriptionplan->payment_mode === 'one-time') {
            $this->stripe->prices->update(
                $subscriptionplan->plan_id,
                ['active' => false]
            );
        } elseif ($subscriptionplan->payment_mode === 'recurring') {
            try {
                $this->stripe->plans->delete(
                    $subscriptionplan->plan_id,
                    []
                );
            } catch (\Throwable $th) {
                return back()->with('error', $th->getMessage());
            }
        }

        if ($attributes['creditsInfo'] ?? false) {
            $creditsInfo = $attributes['creditsInfo'];
        }
        unset($attributes['creditsInfo']);

        if ($attributes['thumbnail']) {
            $attributes['thumbnail'] =
                $fileManagement->uploadFile(
                    file: $attributes['thumbnail'] ?? false,
                    deleteOldFile: $subscriptionplan->thumbnail ?? false,
                    oldFile: $subscriptionplan->thumbnail,
                    path: 'assets/app/images/subscriptionplans/id_' . $subscriptionplan['id'] . '/thumbnail',
                );
        } else if ($subscriptionplan->thumbnail) {
            $fileManagement->deleteFile(
                fileUrl: $subscriptionplan->thumbnail
            );
        }

        if ($creditsInfo ?? false) {
            (new CreditController)->update($creditsInfo, $subscriptionplan->id);
        }

        if ($attributes['payment_mode'] === 'one-time') {
            $stripePlan = $this->stripe->prices->create([
                'unit_amount' => $attributes["price"] * 100,
                'currency' => 'aud',
                'active' => $attributes['published'] == 1,
                'product_data' => ['name' => $attributes['title'], 'metadata' => ['description' => $attributes['description'], 'thumbnail_url' => $subscriptionplan->thumbnail_url], 'unit_label' => $subscriptionplan->id],
            ]);
        } elseif ($attributes['payment_mode'] === 'recurring') {
            $stripePlan = $this->stripe->plans->create([
                'amount' => $attributes["price"] * 100,
                'currency' => 'aud',
                'interval' => $attributes["payment_interval"],
                'active' => $attributes["published"] == 1,
                "interval_count" => $attributes["payment_interval_count"],
                'product' => ['name' => $attributes['title'], 'metadata' => ['description' => $attributes['description'], 'thumbnail_url' => $subscriptionplan->thumbnail_url], 'unit_label' => $subscriptionplan->id],
            ]);
        }

        $subscriptionplan->update($attributes);

        $subscriptionplan->update(['plan_id' => $stripePlan->id]);

        return back()->with('success', 'Membership plan Updated!');
    }

    public function destroy(Subscriptionplan $subscriptionplan)
    {

        try {
            if ($subscriptionplan->payment_mode === 'one-time') {
                $this->stripe->prices->update(
                    $subscriptionplan->plan_id,
                    ['active' => false]
                );
            } elseif ($subscriptionplan->payment_mode === 'recurring') {
                    $this->stripe->plans->delete(
                        $subscriptionplan->plan_id,
                        []
                    );
            }
            $subscriptionplan->credits()->delete();
            $subscriptionplan->delete();
            Storage::disk('public')->deleteDirectory('assets/app/images/subscriptionplans/id_' . $subscriptionplan['id']);
            return redirect('/admin/dashboard/subscriptionplans')->with('success', 'Membership plan Deleted!');
        } catch (\Throwable $th) {
            $subscriptionplan->credits()->delete();
            $subscriptionplan->delete();
            Storage::disk('public')->deleteDirectory('assets/app/images/subscriptionplans/id_' . $subscriptionplan['id']);
            return redirect('/admin/dashboard/subscriptionplans')->with('success', 'Membership plan Deleted! But, ' . $th->getMessage());
        }
    }

    protected function validateSubscriptionplan(?Subscriptionplan $subscriptionplan = null): array
    {
        $subscriptionplan ??= new Subscriptionplan();

        return request()->validate(
            [
                'title' => 'required|min:3|max:50',
                'slug' => ['required', Rule::unique('subscriptionplans', 'slug')->ignore($subscriptionplan)],
                'price' => 'required|numeric',
                'payment_interval_count' => 'required|numeric',
                'payment_interval' => 'required',
                'payment_mode' => 'required',
                'description' => 'required|max:1000',
                'published' => 'required|boolean',
                'limit_purchase' => 'required|boolean',
                'thumbnail' => is_string(request()->input('thumbnail')) ? 'required' : ['required', 'mimes:jpeg,png', 'max:2048'],
                'creditsInfo' => 'nullable',
                'creditsInfo.sessionGenCredits.credits' => Rule::requiredIf(isset(request()->creditsInfo)),
                'creditsInfo.facilityGenCredits.credits' => Rule::requiredIf(isset(request()->creditsInfo)),
            ],
            [
                'slug' => 'Enter a unique slug for your the subscriptionplan\'s link',
                'thumbnail' => 'Upload thumbnail as jpg/png format with size less than 2MB',
                'creditsInfo.sessionGenCredits.credits' => 'Enter Session credits',
                'creditsInfo.facilityGenCredits.credits' => 'Enter Facility credits',
                'payment_mode' => 'Enter pricing mode',
                'payment_interval_count' => 'Enter payment interval count',
                'payment_interval' => 'Enter payment interval'
            ]
        );
    }
}
