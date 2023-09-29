<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
// use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Subscription;
use Illuminate\Validation\Rule;
use App\Services\FileManagement;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    //

    public function index()
    {
        return Inertia::render('Admin/Dashboard/Facilities/Index', [
            'facilities' => Facility::filter(
                request(['search', 'dateStart', 'dateEnd', 'sortBy', 'published'])
            )
                ->paginate(3)->withQueryString(),
            'filters' => Request::only(['search', 'sortBy', 'dateStart', 'dateEnd', 'published']),
        ]);
    }

    public function create()
    {
        $subscriptions = Subscription::where('published', 1)->get();
        foreach ($subscriptions as $subscription) {
            $subscription->price = '';
        }
        $subscriptionsPrices = $subscriptions;

        return Inertia::render('Admin/Dashboard/Facilities/Create', [
            'subscriptionsPrices' => $subscriptionsPrices,

        ]);
    }

    public function store(FileManagement $fileManagement)
    {

        $attributes = $this->validateFacility();

        if ($attributes['thumbnail'] ?? false) {
            $thumbnail = $attributes['thumbnail'];
        }
        unset($attributes['thumbnail']);

        if (isset($attributes['subscriptionsPrices'])) {
            $subscriptionsPrices = $attributes['subscriptionsPrices'];
        }
        unset($attributes['subscriptionsPrices']);

        $facility = Facility::create($attributes);

        if ($thumbnail ?? false) {
            $thumbnail = $fileManagement->uploadFile(
                file: $thumbnail,
                path: 'assets/app/images/facilities/id_' . $facility['id'] . '/thumbnail'
            );
            $facility->thumbnail = $thumbnail;
        }

        if (isset($subscriptionsPrices)) {
            foreach ($subscriptionsPrices as $subscriptionPrice) {
                $facility->subscriptions()->attach($subscriptionPrice["id"], ['price' => $subscriptionPrice["price"]]);
            }
        }

        $facility->save();

        if (Auth::guard('web')->check()) {
            if (Auth::user()->can('admin')) {
                return redirect('/admin/dashboard/facilities')->with('success', 'Facility has been created.');
            }
            return;
        }
        return;
    }

    public function edit(Facility $facility)
    {
        $subscriptions = Subscription::where('published', 1)->get();
        foreach ($subscriptions as $subscription) {
            $subscription->price = $facility->subscriptions()->find($subscription->id)->pivot->price;
        }
        $subscriptionsPrices = $subscriptions;
        return Inertia::render('Admin/Dashboard/Facilities/Edit', [
            'facility' => $facility,
            'subscriptionsPrices' => $subscriptionsPrices,
        ]);

    }

    public function update(Facility $facility)
    {
        $attributes = $this->validateFacility($facility);

        $fileManagement = new FileManagement();

        if ($attributes['thumbnail']) {
            $attributes['thumbnail'] =
            $fileManagement->uploadFile(
                file: $attributes['thumbnail'] ?? false,
                deleteOldFile: $facility->thumbnail ?? false,
                oldFile: $facility->thumbnail,
                path: 'assets/app/images/facilities/id_' . $facility['id'] . '/thumbnail', //($facility['email'] !== $attributes['email'] ? $attributes['email'] : $facility['email']) . '/thumbnail',
            );
        } else if ($facility->thumbnail) {
            $fileManagement->deleteFile(
                fileUrl: $facility->thumbnail
            );
        }

        if (isset($attributes['subscriptionsPrices'])) {
            $subscriptionsPrices = $attributes['subscriptionsPrices'];
        }
        unset($attributes['subscriptionsPrices']);

        $facility->subscriptions()->sync([]);

        if (isset($subscriptionsPrices)) {
            foreach ($subscriptionsPrices as $subscriptionPrice) {
                $facility->subscriptions()->attach($subscriptionPrice["id"], ['price' => $subscriptionPrice["price"]]);
            }
        }

        $facility->update($attributes);

        return back()->with('success', 'Facility Updated!');

    }

    public function destroy(Facility $facility)
    {
        // dd($teacher->course->all());
        $facility->delete();
        Storage::disk('public')->deleteDirectory('assets/app/images/facilities/id_' . $facility['id']);

        return redirect('/admin/dashboard/facilities')->with('success', 'Facility Deleted!');
    }

    protected function validateFacility(?Facility $facility = null): array
    {
        $facility ??= new Facility();

        return request()->validate(
            [
                'title' => 'required|min:3|max:50',
                'slug' => ['required', Rule::unique('facilities', 'slug')->ignore($facility)],
                'price' => 'required|numeric|max:100000',
                'description' => 'required|max:1000',
                'excerpt' => 'required|max:1000',
                'published' => 'required|boolean',
                'subscriptionsPrices' => 'nullable',
                'thumbnail' => is_string(request()->input('thumbnail')) ? 'required' : 'required|mimes:jpg,bmp,png|max:2096',
            ],
            [
                'slug' => 'Enter a unique slug for your the subscription\'s link',
                'thumbnail' => 'Upload thumbnail as jpg/png format with size less than 2MB',
            ]
        );
    }
}
