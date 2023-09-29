<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Subscription;
use Illuminate\Validation\Rule;
use App\Services\FileManagement;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class SubscriptionController extends Controller
{
    public function index()
    {

        // dd(User::all());

        return Inertia::render('Admin/Dashboard/Subscriptions/Index', [
            'subscriptions' => Subscription::filter(
                request(['search', 'dateStart', 'dateEnd', 'sortBy', 'published'])
            )
                ->paginate(3)->withQueryString(),
            'filters' => Request::only(['search', 'sortBy', 'dateStart', 'dateEnd', 'published']),
        ]);
    }

    public function create() {
        return Inertia::render('Admin/Dashboard/Subscriptions/Create');
    }

    public function store(FileManagement $fileManagement) {
        $attributes = $this->validateSubscription();

        if ($attributes['thumbnail'] ?? false) {
            $thumbnail = $attributes['thumbnail'];
            $attributes['thumbnail']=false;
        }

        $subscription = Subscription::create($attributes);

        if ($thumbnail ?? false) {
            $thumbnail = $fileManagement->uploadFile(
                file:$thumbnail,
                path:'assets/app/images/subscriptions/id_' .$subscription['id']. '/thumbnail'
            );
            $subscription->thumbnail =  $thumbnail;
            $subscription->save();
        }
        if (Auth::guard('web')->check()) {
            if (Auth::user()->can('admin')) {
                return redirect('/admin/dashboard/subscriptions')->with('success', 'Subscription has been created.');
            }
            return;
        }
        return;
    
    }

    public function edit(Subscription $subscription)
    {
        return Inertia::render('Admin/Dashboard/Subscriptions/Edit', [
            'subscription' => $subscription,
        ]);

    }

    public function update(Subscription $subscription)
    {

        $attributes = $this->validateSubscription($subscription);
        $fileManagement = new FileManagement();

        if ($attributes['thumbnail']) {
            $attributes['thumbnail'] =
            $fileManagement->uploadFile(
                file:$attributes['thumbnail'] ?? false,
                deleteOldFile:$subscription->thumbnail ?? false,
                oldFile:$subscription->thumbnail,
                path:'assets/app/images/subscriptions/id_' .$subscription['id'].'/thumbnail', 
            );
        } else if ($subscription->thumbnail) {
            $fileManagement->deleteFile(
                fileUrl:$subscription->thumbnail
            );
        }

        $subscription->update($attributes);

        return back()->with('success', 'Subscription Updated!');
    }

    public function destroy(Subscription $subscription)
    {
        // dd($teacher->course->all());
        $subscription->delete();
        Storage::disk('public')->deleteDirectory('assets/app/images/subscriptions/id_' . $subscription['id']);

        return redirect('/admin/dashboard/subscriptions')->with('success', 'Subscription Deleted!');
    }

    protected function validateSubscription( ? Subscription $subscription = null) : array
    {
        $subscription ??= new Subscription();

        return request()->validate(
            [
                'title' => 'required|min:3|max:50',
                'slug' => ['required', Rule::unique('subscriptions', 'slug')->ignore($subscription)],
                'price'=>'required|numeric|max:50',
                'validity'=>'required|numeric|max:50',
                'description'=>'required|max:1000',
                'published' => 'required|boolean',
                'thumbnail' => is_string(request()->input('thumbnail')) ? 'required' : 'required|mimes:jpeg,png |max:2096',
            ],
            [
                'slug' => 'Enter a unique slug for your the subscription\'s link',
                'thumbnail' =>'Upload thumbnail as jpg/png format with size less than 2MB',
            ]
        );
    }
}
