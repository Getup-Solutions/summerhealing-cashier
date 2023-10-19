<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Inertia\Inertia;
use App\Models\Level;
use App\Models\Course;
use App\Models\Trainer;
use App\Models\Agegroup;
use Illuminate\Validation\Rule;
use App\Models\Subscriptionplan;
use App\Services\FileManagement;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    //
    public function index()
    {

        return Inertia::render('Admin/Dashboard/Courses/Index', [
            'courses' => Course::filter(
                request(['search', 'dateStart', 'dateEnd', 'sortBy', 'published', 'subscriptionplans'])
            )
                ->paginate(3)->withQueryString(),
            'filters' => Request::only(['search', 'sortBy', 'dateStart', 'dateEnd', 'published', 'subscriptionplans']),
            'subscriptionplans' => Subscriptionplan::all(),

        ]);
    }

    public function create()
    {
        // dd(Role::where('value', 'TRAINER_ROLE')->first()->users()->get());
        return Inertia::render('Admin/Dashboard/Courses/Create', [
            'agegroups' => Agegroup::all(),
            'levels' => Level::all(),
            'trainers' =>Trainer::all(),
            'subscriptionplans' => Subscriptionplan::where('published', 1)->get(),

        ]);
    }

    public function store(FileManagement $fileManagement)
    {

        $attributes = $this->validateCourse();

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

        $course = Course::create($attributes);

        if ($thumbnail ?? false) {
            $thumbnail = $fileManagement->uploadFile(
                file: $thumbnail,
                path: 'assets/app/images/courses/id_' . $course['id'] . '/thumbnail'
            );
            $course->thumbnail = $thumbnail;

        }

        if (isset($trainers)) {
            $course->trainers()->sync($trainers);
        }

        if (isset($subscriptionplansPrices)) {
            foreach ($subscriptionplansPrices as $subscriptionplanPrice) {
                $course->subscriptionplans()->attach($subscriptionplanPrice["id"], ['course_price' => $subscriptionplanPrice["price"]]);
            }
        }

        $course->save();

        if (Auth::guard('web')->check()) {
            if (Auth::user()->can('admin')) {
                return redirect('/admin/dashboard/courses')->with('success', 'Course has been created.');
            }
            return;
        }
        return;

    }

    public function edit(Course $course)
    {
        $subscriptionplans = Subscriptionplan::where('published', 1)->get();
        $selectedSubscriptionplansIds = $course->subscriptionplans()->pluck('subscriptionplan_id')->toArray();
        foreach ($subscriptionplans as $subscriptionplan) {
            if (in_array($subscriptionplan->id, $selectedSubscriptionplansIds)) {
                $subscriptionplan->price = $course->subscriptionplans()->find($subscriptionplan->id)->pivot->course_price;

            } else {
                $subscriptionplan->price =(float)$course->price;
            }
        }
        $subscriptionplansPrices = $subscriptionplans;


        return Inertia::render('Admin/Dashboard/Courses/Edit', [
            'course' => $course,
            'agegroups' => Agegroup::all(),
            'levels' => Level::all(),
            'trainers' => Trainer::all(),
            'subscriptionplansPrices' => $subscriptionplansPrices,
            'subscriptionplansSelected' => $course->subscriptionplans()->pluck('subscriptionplan_id'),
            'trainersSelected' => $course->trainers()->pluck('trainer_id'),
        ]);
    }

    public function update(Course $course){
        $attributes = $this->validateCourse($course);

        $fileManagement = new FileManagement();

        if ($attributes['thumbnail']) {
            $attributes['thumbnail'] =
            $fileManagement->uploadFile(
                file:$attributes['thumbnail'] ?? false,
                deleteOldFile:$course->thumbnail ?? false,
                oldFile:$course->thumbnail,
                path:'assets/app/images/courses/id_' .$course['id'].'/thumbnail', //($course['email'] !== $attributes['email'] ? $attributes['email'] : $course['email']) . '/thumbnail',
            );
        } else if ($course->thumbnail) {
            $fileManagement->deleteFile(
                fileUrl:$course->thumbnail
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
            $course->trainers()->sync($trainers);
        }

        $course->subscriptionplans()->sync([]);

        if (isset($subscriptionplansPrices)) {
            foreach ($subscriptionplansPrices as $subscriptionplanPrice) {
                $course->subscriptionplans()->attach($subscriptionplanPrice["id"], ['course_price' => $subscriptionplanPrice["price"]]);
            }
        }

        $course->update($attributes);

        return back()->with('success', 'Course Updated!');

    }

    public function destroy(Course $course)
    {
        // dd($teacher->course->all());
        $course->delete();
        Storage::disk('public')->deleteDirectory('assets/app/images/courses/id_' . $course['id']);

        return redirect('/admin/dashboard/courses')->with('success', 'Course Deleted!');
    }

    protected function validateCourse(?Course $course = null): array
    {
        $course ??= new Course();

        return request()->validate(
            [
                'title' => 'required|min:3|max:50',
                'slug' => ['required', Rule::unique('courses', 'slug')->ignore($course)],
                'price' => 'required|numeric|max:100000',
                'description' => 'required|max:1000',
                'excerpt' => 'required|max:1000',
                'agegroup_id' => 'required|numeric',
                'video_url' => 'required|max:200',
                'level' => 'required',
                'trainers' => 'required',
                'subscriptionplansPrices' => 'required',
                'published' => 'required|boolean',
                'thumbnail' => is_string(request()->input('thumbnail')) ? 'required' : ['required','mimes:jpeg,png','max:2048'],
            ],
            [
                'slug' => 'Enter a unique slug for your the subscriptionplan\'s link',
                'thumbnail' => 'Upload thumbnail as jpg/png format with size less than 2MB',
            ]
        );
    }

}
