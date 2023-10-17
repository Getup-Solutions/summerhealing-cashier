<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\ScheduleController;
use App\Models\Agegroup;
use App\Models\Day;
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
            'days' => Day::all(),
            'subscriptionplans' => Subscriptionplan::where('published', 1)->get(),

        ]);
    }

    public function store(FileManagement $fileManagement)
    {

        $attributes = $this->validateSession();
        // dd($attributes);


        if ($attributes['scheduleInfo'] ?? false) {
            $scheduleInfo = $attributes['scheduleInfo'];
        }
        unset($attributes['scheduleInfo']);


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

        if (isset($scheduleInfo)) {
            $scheduleInfo["scheduleable_type"] = 'App\Models\Session';
            $scheduleInfo["scheduleable_id"] = $session->id;
            $scheduleInfo["title"] = $session->title;
            $scheduleController = new ScheduleController();
            $scheduleController->store($scheduleInfo);
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
                $subscriptionplan->price = (float)$session->price;
            }
        }
        $subscriptionplansPrices = $subscriptionplans;
        // dd($session->schedule()->first());
        if ($schedule_id = $session->schedule()->first()) {
            $schedule_id = $session->schedule()->first()->id;
            $daysSelected = $session->schedule()->first()->days()->get()->pluck('id')->toArray();
            $daysEvent = [];
            for ($i = 0; $i < 7; $i++) {
                $daysEvent[$i] = in_array($i + 1, $daysSelected) ? Day::find($i + 1)->events()->wherePivot('schedule_id', $schedule_id)->get()->toArray() : [];
            }
        }

        $dataTosend = [
            'session' => $session,
            'agegroups' => Agegroup::all(),
            'levels' => Level::all(),
            'trainers' => Trainer::all(),
            'subscriptionplansPrices' => $subscriptionplansPrices,
            'subscriptionplansSelected' => $session->subscriptionplans()->pluck('subscriptionplan_id'),
            'trainersSelected' => $session->trainers()->pluck('trainer_id'),
            // 'schedule'=> $session->schedule()->first(),
            'days'=>Day::all(),
            // 'daysSelectedData'=>isset($daysSelected) ? $daysSelected : [],
            // 'daysEventData'=> isset($daysEvent) ? $daysEvent : []
        ];

        if (isset($schedule_id)) {
            array_merge([
                'schedule' => $session->schedule()->first(),
                'daysSelectedData' => $daysSelected,
                'daysEventData' => $daysEvent
            ]);
        }

        return Inertia::render('Admin/Dashboard/Sessions/Edit', $dataTosend);
    }

    public function update(Session $session)
    {
        $attributes = $this->validateSession($session);
        // dd($attributes);

        if ($attributes['scheduleInfo'] ?? false) {
            $scheduleInfo = $attributes['scheduleInfo'];
        }
        unset($attributes['scheduleInfo']);

        $fileManagement = new FileManagement();

        if ($attributes['thumbnail']) {
            $attributes['thumbnail'] =
                $fileManagement->uploadFile(
                    file: $attributes['thumbnail'] ?? false,
                    deleteOldFile: $session->thumbnail ?? false,
                    oldFile: $session->thumbnail,
                    path: 'assets/app/images/sessions/id_' . $session['id'] . '/thumbnail', //($session['email'] !== $attributes['email'] ? $attributes['email'] : $session['email']) . '/thumbnail',
                );
        } else if ($session->thumbnail) {
            $fileManagement->deleteFile(
                fileUrl: $session->thumbnail
            );
        }

        if (isset($attributes['trainers'])) {
            $trainers = $attributes['trainers'];
        }
        unset($attributes['trainers']);

        if (isset($attributes['subscriptionplansPrices'])) {
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

        if (isset($scheduleInfo)) {
            if(isset($scheduleInfo["id"])){
                $scheduleController = new ScheduleController();
                $scheduleController->update($scheduleInfo);
            } else {
                $scheduleInfo["scheduleable_type"] = 'App\Models\Session';
                $scheduleInfo["scheduleable_id"] = $session->id;
                $scheduleInfo["title"] = $session->title;
                $scheduleController = new ScheduleController();
                $scheduleController->store($scheduleInfo);
            }
            // $scheduleInfo["scheduleable_type"] = 'App\Models\Session';
            // $scheduleInfo["scheduleable_id"] = $session->id;

        }

        $session->update($attributes);

        return back()->with('success', 'Session Updated!');
    }

    public function destroy(Session $session)
    {
        // dd($teacher->course->all());
        // dd($session->schedule()->first());
        $schedule = $session->schedule()->first();
        $scheduleController = new ScheduleController();
        $scheduleController->destroy($schedule);
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
                'slug' => ['required',], // Rule::unique('sessions', 'slug')->ignore($session)],
                'price' => 'required|numeric|max:100000',
                'description' => 'required|max:1000',
                'excerpt' => 'required|max:1000',
                'agegroup_id' => 'required|numeric',
                'video_url' => 'required|max:200',
                'size' => 'required',
                'level' => 'required',
                'trainers' => 'nullable',
                'subscriptionplansPrices' => 'nullable',
                'published' => 'required|boolean',
                'scheduleInfo' => 'nullable',
                'scheduleInfo.start_date' => Rule::requiredIf(isset(request()->scheduleInfo)),
                'scheduleInfo.end_date' => Rule::requiredIf(isset(request()->scheduleInfo)),
                'scheduleInfo.days' => Rule::requiredIf(isset(request()->scheduleInfo)),
                // 'thumbnail' => is_string(request()->input('thumbnail')) ? 'required' : 'required|mimes:jpeg,png |max:2096',
                'thumbnail' => 'nullable',
            ],
            [
                'slug' => 'Enter a unique slug for your the subscriptionplan\'s link',
                'scheduleInfo.start_date' => 'Add a starting date for the schedule',
                'scheduleInfo.end_date' => 'Add a end date for the schedule',
                'scheduleInfo.days' => 'Select days and create schedule',
                'thumbnail' => 'Upload thumbnail as jpg/png format with size less than 2MB',
            ]
        );
    }
}
