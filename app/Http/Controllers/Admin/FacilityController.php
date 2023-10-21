<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
// use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Trainer;
use App\Models\Agegroup;
use App\Models\Level;
use App\Models\Subscriptionplan;
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
        // $subscriptionplans = Subscriptionplan::where('published', 1)->get();
        // foreach ($subscriptionplans as $subscriptionplan) {
        //     $subscriptionplan->price = '';
        // }
        // $subscriptionplansPrices = $subscriptionplans;

        return Inertia::render('Admin/Dashboard/Facilities/Create', [
            // 'subscriptionplansPrices' => $subscriptionplansPrices,
            'agegroups' => Agegroup::all(),
            'levels' => Level::all(),
            'trainers' =>Trainer::all(),
            'subscriptionplans' => Subscriptionplan::where('published', 1)->get(),

        ]);
    }

    public function store(FileManagement $fileManagement)
    {

        $attributes = $this->validateFacility();

        if ($attributes['thumbnail'] ?? false) {
            $thumbnail = $attributes['thumbnail'];
        }
        unset($attributes['thumbnail']);

        if (isset($attributes['trainers'])) {
            $trainers = $attributes['trainers'];
        }
        unset($attributes['trainers']);

        if (isset($attributes['subscriptionplansPrices'])) {
            $subscriptionplansPrices = $attributes['subscriptionplansPrices'];
        }
        unset($attributes['subscriptionplansPrices']);

        // if (isset($attributes['subscriptionplansPrices'])) {
        //     $subscriptionplansPrices = $attributes['subscriptionplansPrices'];
        // }
        // unset($attributes['subscriptionplansPrices']);

        $facility = Facility::create($attributes);

        if ($thumbnail ?? false) {
            $thumbnail = $fileManagement->uploadFile(
                file: $thumbnail,
                path: 'assets/app/images/facilities/id_' . $facility['id'] . '/thumbnail'
            );
            $facility->thumbnail = $thumbnail;
        }

        if (isset($trainers)) {
            $facility->trainers()->sync($trainers);
        }

        if (isset($subscriptionplansPrices)) {
            foreach ($subscriptionplansPrices as $subscriptionplanPrice) {
                $facility->subscriptionplans()->attach($subscriptionplanPrice["id"], ['facility_price' => $subscriptionplanPrice["price"]]);
            }
        }

        // if (isset($subscriptionplansPrices)) {
        //     foreach ($subscriptionplansPrices as $subscriptionplanPrice) {
        //         // if($subscriptionplanPrice["price"]<=$facility->price){
        //             $facility->subscriptionplans()->attach($subscriptionplanPrice["id"], ['facility_price' => $subscriptionplanPrice["price"] ?? 0]);
        //         // }    
        //     }
        // }

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
        $subscriptionplans = Subscriptionplan::where('published', 1)->get();
        $selectedSubscriptionplansIds = $facility->subscriptionplans()->pluck('subscriptionplan_id')->toArray();
        foreach ($subscriptionplans as $subscriptionplan) {
            if (in_array($subscriptionplan->id, $selectedSubscriptionplansIds)) {
                $subscriptionplan->price = $facility->subscriptionplans()->find($subscriptionplan->id)->pivot->facility_price;

            } else {
                $subscriptionplan->price =(float)$facility->price;
            }
        }
        $subscriptionplansPrices = $subscriptionplans;
        // $subscriptionplans = $facility->subscriptionplans()->get();
        // // dd($facility->subscriptionplans()->get());
        // foreach ($subscriptionplans as $subscriptionplan) {
        //     // dd($facility->subscriptionplans()->get());
        //     $subscriptionplan->price = $facility->subscriptionplans()->find($subscriptionplan->id)->pivot->facility_price;
        // }
        // $subscriptionplansPrices = $subscriptionplans;
        return Inertia::render('Admin/Dashboard/Facilities/Edit', [
            'facility' => $facility,
            // 'subscriptionplansPrices' => $subscriptionplansPrices,
            'trainers' => Trainer::all(),
            'subscriptionplansPrices' => $subscriptionplansPrices,
            'subscriptionplansSelected' => $facility->subscriptionplans()->pluck('subscriptionplan_id'),
            'trainersSelected' => $facility->trainers()->pluck('trainer_id'),
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

        if (isset($attributes['trainers'])) {
            $trainers = $attributes['trainers'];
        }
        unset($attributes['trainers']);

        if(isset($attributes['subscriptionplansPrices'])) {
            $subscriptionplansPrices = $attributes['subscriptionplansPrices'];
        }
        unset($attributes['subscriptionplansPrices']);


        if (isset($trainers)) {
            $facility->trainers()->sync($trainers);
        }

        $facility->subscriptionplans()->sync([]);

        if (isset($subscriptionplansPrices)) {
            foreach ($subscriptionplansPrices as $subscriptionplanPrice) {
                $facility->subscriptionplans()->attach($subscriptionplanPrice["id"], ['facility_price' => $subscriptionplanPrice["price"]]);
            }
        }

        // if (isset($attributes['subscriptionplansPrices'])) {
        //     $subscriptionplansPrices = $attributes['subscriptionplansPrices'];
        // }
        // unset($attributes['subscriptionplansPrices']);

        // $facility->subscriptionplans()->sync([]);

        // if (isset($subscriptionplansPrices)) {
        //     foreach ($subscriptionplansPrices as $subscriptionplanPrice) {
        //         if($subscriptionplanPrice["price"]<$facility->price){
        //             $facility->subscriptionplans()->attach($subscriptionplanPrice["id"], ['facility_price' => $subscriptionplanPrice["price"]]);
        //         }    
        //     }
        // }

        $facility->update($attributes);

        return back()->with('success', 'Facility Updated!');

    }

    public function destroy(Facility $facility)
    {
        // dd($teacher->facility->all());
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
                'trainers' => 'required',
                'subscriptionplansPrices' => 'nullable',
                'published' => 'required|boolean',
                'thumbnail' => is_string(request()->input('thumbnail')) ? 'required' :  ['required','mimes:jpeg,png','max:2048'],
            ],
            [
                'slug' => 'Enter a unique slug for your the facility\'s link',
                'thumbnail' => 'Upload thumbnail as jpg/png format with size less than 2MB',
            ]
        );
    }
}
