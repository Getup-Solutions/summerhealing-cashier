<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        return Inertia::render('Admin/Dashboard/Subscriptionplans/Create');
    }

    public function store(FileManagement $fileManagement)
    {
        $attributes = $this->validateSubscriptionplan();

        $stripePlan = $this->stripe->plans->create([
            'amount' => $attributes["price"] * 100,
            'currency' => 'inr',
            'interval' => 'day',
            'active' => $attributes["published"] == 1,
            "interval_count" => $attributes["validity"],
            'product' => ['name' => $attributes["title"]],
        ]);

        if ($attributes['thumbnail'] ?? false) {
            $thumbnail = $attributes['thumbnail'];
            $attributes['thumbnail'] = false;
        }

        $subscriptionplan = Subscriptionplan::create($attributes);

        $subscriptionplan->plan_id = $stripePlan->id;

        if ($thumbnail ?? false) {
            $thumbnail = $fileManagement->uploadFile(
                file: $thumbnail,
                path: 'assets/app/images/subscriptionplans/id_' . $subscriptionplan['id'] . '/thumbnail'
            );
            $subscriptionplan->thumbnail = $thumbnail;
            $subscriptionplan->save();

        }

        if (Auth::guard('web')->check()) {
            if (Auth::user()->can('admin')) {
                return redirect('/admin/dashboard/subscriptionplans')->with('success', 'Subscription plan has been created.');
            }
            return;
        }
        return;

    }

    public function edit(Subscriptionplan $subscriptionplan)
    {
        return Inertia::render('Admin/Dashboard/Subscriptionplans/Edit', [
            'subscriptionplan' => $subscriptionplan,
        ]);

    }

    public function update(Subscriptionplan $subscriptionplan, FileManagement $fileManagement)
    {

        $attributes = $this->validateSubscriptionplan($subscriptionplan);
        try {
            $this->stripe->plans->delete(
                $subscriptionplan->plan_id,
                []
            );
        } catch (\Throwable $th) {
            
        }

        $stripePlan = $this->stripe->plans->create([
            'amount' => $attributes["price"] * 100,
            'currency' => 'inr',
            'interval' => 'day',
            'active' => $attributes["published"] == 1,
            "interval_count" => $attributes["validity"],
            'product' => ['name' => $attributes["title"]],
        ]);

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

        $attributes["plan_id"] = $stripePlan->id;
        $subscriptionplan->update($attributes);

        return back()->with('success', 'Subscription plan Updated!');
    }

    public function destroy(Subscriptionplan $subscriptionplan)
    {
        try {
            $this->stripe->plans->delete(
                $subscriptionplan->plan_id,
                []
            );
        } catch (\Throwable $th) {
            
        }
        $subscriptionplan->delete();
        Storage::disk('public')->deleteDirectory('assets/app/images/subscriptionplans/id_' . $subscriptionplan['id']);

        return redirect('/admin/dashboard/subscriptionplans')->with('success', 'Subscription plan Deleted!');
    }

    protected function validateSubscriptionplan(?Subscriptionplan $subscriptionplan = null): array
    {
        $subscriptionplan ??= new Subscriptionplan();

        return request()->validate(
            [
                'title' => 'required|min:3|max:50',
                'slug' => ['required', Rule::unique('subscriptionplans', 'slug')->ignore($subscriptionplan)],
                'price' => 'required|numeric',
                'validity' => 'required|numeric',
                'description' => 'required|max:1000',
                'published' => 'required|boolean',
                'thumbnail' => is_string(request()->input('thumbnail')) ? 'required' :  ['required','mimes:jpeg,png','max:2048'],
            ],
            [
                'slug' => 'Enter a unique slug for your the subscriptionplan\'s link',
                'thumbnail' => 'Upload thumbnail as jpg/png format with size less than 2MB',
            ]
        );
    }
}
