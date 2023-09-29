<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Level;
use App\Models\Course;
use App\Models\Trainer;
use App\Models\Agegroup;
use App\Models\Subscription;
use Illuminate\Validation\Rule;
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
                request(['search', 'dateStart', 'dateEnd', 'sortBy', 'published', 'subscriptions'])
            )
                ->paginate(3)->withQueryString(),
            'filters' => Request::only(['search', 'sortBy', 'dateStart', 'dateEnd', 'published', 'subscriptions']),
            'subscriptions' => Subscription::all(),

        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Dashboard/Courses/Create', [
            'agegroups' => Agegroup::all(),
            'levels' => Level::all(),
            'trainers' => Trainer::all(),
            'subscriptions' => Subscription::where('published', 1)->get(),

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

        if (isset($attributes['subscriptionsPrices'])) {
            $subscriptionsPrices = $attributes['subscriptionsPrices'];
        }
        unset($attributes['subscriptionsPrices']);

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

        if (isset($subscriptionsPrices)) {
            foreach ($subscriptionsPrices as $subscriptionPrice) {
                $course->subscriptions()->attach($subscriptionPrice["id"], ['price' => $subscriptionPrice["price"]]);
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
        $subscriptions = Subscription::where('published', 1)->get();
        $selectedSubscriptionsIds = $course->subscriptions()->pluck('subscription_id')->toArray();
        foreach ($subscriptions as $subscription) {
            if (in_array($subscription->id, $selectedSubscriptionsIds)) {
                $subscription->price = $course->subscriptions()->find($subscription->id)->pivot->price;

            } else {
                $subscription->price =(float)$course->price;
            }
        }
        $subscriptionsPrices = $subscriptions;

        return Inertia::render('Admin/Dashboard/Courses/Edit', [
            'course' => $course,
            'agegroups' => Agegroup::all(),
            'levels' => Level::all(),
            'trainers' => Trainer::all(),
            'subscriptionsPrices' => $subscriptionsPrices,
            'subscriptionsSelected' => $course->subscriptions()->pluck('subscription_id'),
            'trainersSelected' => $course->trainers()->pluck('trainer_id'),
        ]);
    }

    public function update(Course $course){
        $attributes = $this->validateCourse();

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

        if(isset($attributes['subscriptionsPrices'])) {
            $subscriptionsPrices = $attributes['subscriptionsPrices'];
        }
        unset($attributes['subscriptionsPrices']);


        if (isset($trainers)) {
            $course->trainers()->sync($trainers);
        }

        $course->subscriptions()->sync([]);

        if (isset($subscriptionsPrices)) {
            foreach ($subscriptionsPrices as $subscriptionPrice) {
                $course->subscriptions()->attach($subscriptionPrice["id"], ['price' => $subscriptionPrice["price"]]);
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
                'trainers' => 'nullable',
                'subscriptionsPrices' => 'nullable',
                'published' => 'required|boolean',
                'thumbnail' => is_string(request()->input('thumbnail')) ? 'required' : 'required|mimes:jpeg,png |max:2096',
            ],
            [
                'slug' => 'Enter a unique slug for your the subscription\'s link',
                'thumbnail' => 'Upload thumbnail as jpg/png format with size less than 2MB',
            ]
        );
    }

}
