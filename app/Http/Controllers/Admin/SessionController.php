<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agegroup;
use App\Models\Level;
use App\Models\Session;
// use Illuminate\Http\Request;
use App\Models\Subscriptionplan;
use App\Models\Trainer;
use App\Services\FileManagement;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SessionController extends Controller
{
    //
    public function index()
    {

        return Inertia::render('Admin/Dashboard/Sessions/Index', [
            'sessions' => Session::filter(
                request(['search', 'dateStart', 'dateEnd', 'sortBy', 'published', 'subscriptionplans'])
            )
                ->paginate(3)->withQueryString(),
            'filters' => Request::only(['search', 'sortBy', 'dateStart', 'dateEnd', 'published', 'subscriptionplans']),
            'subscriptionplans' => Subscriptionplan::all(),

        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Dashboard/Sessions/Create', [
            'agegroups' => Agegroup::all(),
            'levels' => Level::all(),
            'trainers' => Trainer::all(),
            'subscriptionplans' => Subscriptionplan::where('published', 1)->get(),

        ]);
    }

    public function store(FileManagement $fileManagement)
    {

        $attributes = $this->validateSession();

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

        $session = Session::create($attributes);

        if ($thumbnail ?? false) {
            $thumbnail = $fileManagement->uploadFile(
                file: $thumbnail,
                path: 'assets/app/images/sessions/id_' . $session['id'] . '/thumbnail'
            );
            $session->thumbnail = $thumbnail;

        }

        if (isset($trainers)) {
            $session->trainers()->sync($trainers);
        }

        if (isset($subscriptionplansPrices)) {
            foreach ($subscriptionplansPrices as $subscriptionplanPrice) {
                $session->subscriptionplans()->attach($subscriptionplanPrice["id"], ['session_price' => $subscriptionplanPrice["price"]]);
            }
        }

        $session->save();

        if (Auth::guard('web')->check()) {
            if (Auth::user()->can('admin')) {
                return redirect('/admin/dashboard/sessions')->with('success', 'Session has been created.');
            }
            return;
        }
        return;

    }

    public function edit(Session $session)
    {
        $subscriptionplans = Subscriptionplan::where('published', 1)->get();
        $selectedSubscriptionplansIds = $session->subscriptionplans()->pluck('subscriptionplan_id')->toArray();
        foreach ($subscriptionplans as $subscriptionplan) {
            if (in_array($subscriptionplan->id, $selectedSubscriptionplansIds)) {
                $subscriptionplan->price = $session->subscriptionplans()->find($subscriptionplan->id)->pivot->session_price;

            } else {
                $subscriptionplan->price =(float)$session->price;
            }
        }
        $subscriptionplansPrices = $subscriptionplans;

        return Inertia::render('Admin/Dashboard/Sessions/Edit', [
            'session' => $session,
            'agegroups' => Agegroup::all(),
            'levels' => Level::all(),
            'trainers' => Trainer::all(),
            'subscriptionplansPrices' => $subscriptionplansPrices,
            'subscriptionplansSelected' => $session->subscriptionplans()->pluck('subscriptionplan_id'),
            'trainersSelected' => $session->trainers()->pluck('trainer_id'),
        ]);
    }

    public function update(Session $session){
        $attributes = $this->validateSession($session);

        $fileManagement = new FileManagement();

        if ($attributes['thumbnail']) {
            $attributes['thumbnail'] =
            $fileManagement->uploadFile(
                file:$attributes['thumbnail'] ?? false,
                deleteOldFile:$session->thumbnail ?? false,
                oldFile:$session->thumbnail,
                path:'assets/app/images/sessions/id_' .$session['id'].'/thumbnail', //($session['email'] !== $attributes['email'] ? $attributes['email'] : $session['email']) . '/thumbnail',
            );
        } else if ($session->thumbnail) {
            $fileManagement->deleteFile(
                fileUrl:$session->thumbnail
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
            $session->trainers()->sync($trainers);
        }

        $session->subscriptionplans()->sync([]);

        if (isset($subscriptionplansPrices)) {
            foreach ($subscriptionplansPrices as $subscriptionplanPrice) {
                $session->subscriptionplans()->attach($subscriptionplanPrice["id"], ['session_price' => $subscriptionplanPrice["price"]]);
            }
        }

        $session->update($attributes);

        return back()->with('success', 'Session Updated!');

    }

    public function destroy(Session $session)
    {
        // dd($teacher->course->all());
        $session->delete();
        Storage::disk('public')->deleteDirectory('assets/app/images/sessions/id_' . $session['id']);
        return redirect('/admin/dashboard/sessions')->with('success', 'Session Deleted!');
    }

    protected function validateSession(?Session $session = null): array
    {
        $session ??= new Session();

        return request()->validate(
            [
                'title' => 'required|min:3|max:50',
                'slug' => ['required', Rule::unique('sessions', 'slug')->ignore($session)],
                'price' => 'required|numeric|max:100000',
                'description' => 'required|max:1000',
                'excerpt' => 'required|max:1000',
                'agegroup_id' => 'required|numeric',
                'video_url' => 'required|max:200',
                'level' => 'required',
                'trainers' => 'nullable',
                'subscriptionplansPrices' => 'nullable',
                'published' => 'required|boolean',
                'thumbnail' => is_string(request()->input('thumbnail')) ? 'required' : 'required|mimes:jpeg,png |max:2096',
            ],
            [
                'slug' => 'Enter a unique slug for your the subscriptionplan\'s link',
                'thumbnail' => 'Upload thumbnail as jpg/png format with size less than 2MB',
            ]
        );
    }
}
