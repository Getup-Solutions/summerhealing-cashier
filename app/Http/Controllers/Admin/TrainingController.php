<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Training;
use Illuminate\Validation\Rule;
use App\Services\FileManagement;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class TrainingController extends Controller
{
    //
    public function index()
    {

        // dd(User::all());
        return Inertia::render('Admin/Dashboard/Trainings/Index', [
            'trainings' => Training::filter(
                request(['search', 'dateStart', 'dateEnd', 'sortBy', 'published'])
            )
                ->paginate(3)->withQueryString(),
            'filters' => Request::only(['search', 'sortBy', 'dateStart', 'dateEnd', 'published']),
            // 'roles' => Role::all(),
        ]);
    }

    public function create() {
        return Inertia::render('Admin/Dashboard/Trainings/Create');
    }

    public function store(FileManagement $fileManagement) {
        $attributes = $this->validateTraining();

        if ($attributes['thumbnail'] ?? false) {
            $thumbnail = $attributes['thumbnail'];
            $attributes['thumbnail']=false;
        }

        $training = Training::create($attributes);

        if ($thumbnail ?? false) {
            $thumbnail = $fileManagement->uploadFile(
                file:$thumbnail,
                path:'assets/app/images/trainings/id_' .$training['id']. '/thumbnail'
            );
            $training->thumbnail =  $thumbnail;
            $training->save();
        }
        if (Auth::guard('web')->check()) {
            if (Auth::user()->can('admin')) {
                return redirect('/admin/dashboard/trainings')->with('success', 'Training has been created.');
            }
            return;
        }
        return;
    
    }

    public function edit(Training $training)
    {
        return Inertia::render('Admin/Dashboard/Trainings/Edit', [
            'training' => $training,
        ]);

    }

    public function update(Training $training)
    {

        $attributes = $this->validateTraining($training);
        $fileManagement = new FileManagement();

        if ($attributes['thumbnail']) {
            $attributes['thumbnail'] =
            $fileManagement->uploadFile(
                file:$attributes['thumbnail'] ?? false,
                deleteOldFile:$training->thumbnail ?? false,
                oldFile:$training->thumbnail,
                path:'assets/app/images/trainings/id_' .$training['id'].'/thumbnail', 
            );
        } else if ($training->thumbnail) {
            $fileManagement->deleteFile(
                fileUrl:$training->thumbnail
            );
        }

        $training->update($attributes);

        return back()->with('success', 'Training Updated!');
    }

    public function destroy(Training $training)
    {
        $training->delete();
        Storage::disk('public')->deleteDirectory('assets/app/images/trainings/id_' . $training['id']);

        return redirect('/admin/dashboard/trainings')->with('success', 'Training Deleted!');
    }

    protected function validateTraining( ? Training $training = null) : array
    {
        $training ??= new Training();

        return request()->validate(
            [
                'title' => 'required|min:3|max:50',
                'slug' => ['required', Rule::unique('trainings', 'slug')->ignore($training)],
                'excerpt'=>'required|max:1000',
                'description'=>'required|max:1000',
                'published' => 'required|boolean',
                'thumbnail' => is_string(request()->input('thumbnail')) ? 'required' :  ['required','mimes:jpeg,png','max:2048'],
            ],
            [
                'slug' => 'Enter a unique slug for your the subscription\'s link',
                'thumbnail' => 'Upload thumbnail as jpg/png format with size less than 2MB',
            ]
        );
    }


}
