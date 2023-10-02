<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Trainer;
use App\Models\Subscription;
use Illuminate\Validation\Rule;
use App\Services\FileManagement;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {

        // dd(User::all());
        return Inertia::render('Admin/Dashboard/Users/Index', [
            'users' => User::filter(
                request(['search', 'dateStart', 'dateEnd', 'sortBy', 'roles'])
            )
                ->with(['roles'])->paginate(3)->withQueryString(),
            'filters' => Request::only(['search', 'sortBy', 'dateStart', 'dateEnd', 'roles']),
            'roles' => Role::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Dashboard/Users/Create', [
            'roles' => Role::where('value', '!=', 'USER_ROLE')->get(),
            'url_params' => Request::only(['first_name', 'last_name', 'email', 'phone_number','lead_id']),
            'subscriptions' => Subscription::where('published',1)->get()
        ]);
    }

    public function store(FileManagement $fileManagement)
    {
        // dd('ss');
        // dd(request()->all());
        $attributes = $this->validateUser();
        // dd($attributes);

        if ($attributes['avatar'] ?? false) {
            $avatar = $attributes['avatar'];
        }
            unset($attributes['avatar']);


        if (isset($attributes['roles'])) {
            $roles = $attributes['roles'];
        }
        unset($attributes['roles']);


        if (isset($attributes['subscriptions'])) {
            $subscriptions = $attributes['subscriptions'];
        }
        unset($attributes['subscriptions']);


        if (isset($attributes['lead_id'])) {
            $lead_id = $attributes['lead_id'];
        }
        unset($attributes['lead_id']);

        $user = User::create($attributes);

        if(isset($lead_id)){
            $lead = Lead::find($lead_id);
            $lead->user_id = $user->id;
            $lead->save();
        }
        
        if ($avatar ?? false) {
            $avatar = $fileManagement->uploadFile(
                file:$avatar,
                path:'assets/app/images/users/id_' .$user['id']. '/avatar'
            );
            $user->avatar =  $avatar;
            $user->save();

        }

        if (isset($roles)) {
            $user->roles()->sync($roles);
        }

        $user->roles()->attach([1]);


        if (isset($subscriptions)) {
            $user->subscriptions()->sync($subscriptions);
        }

        $user->save();

        if (Auth::guard('web')->check()) {
            if (Auth::user()->can('admin')) {
                return redirect('/admin/dashboard')->with('success', 'User has been created.');
            }
            return;
        }
        return;

    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Dashboard/Users/Edit', [
            'user' => $user,
            'user_roles' => $user->roles()->pluck('role_id'),
            'user_subscriptions' => $user->subscriptions()->pluck('subscription_id'),
            'subscriptions' => Subscription::where('published',1)->get(),
            'roles' => Role::where('value', '!=', 'USER_ROLE')->get(['id', 'name', 'value']),
        ]);

    }

    public function update(User $user)
    {

        $attributes = $this->validateUser($user);
        $fileManagement = new FileManagement();

        if ($attributes['avatar']) {
            $attributes['avatar'] =
            $fileManagement->uploadFile(
                file:$attributes['avatar'] ?? false,
                deleteOldFile:$user->avatar ?? false,
                oldFile:$user->avatar,
                path:'assets/app/images/users/id_' .$user['id'].'/avatar', //($user['email'] !== $attributes['email'] ? $attributes['email'] : $user['email']) . '/avatar',
            );
        } else if ($user->avatar) {
            $fileManagement->deleteFile(
                fileUrl:$user->avatar
            );
        }

        if (isset($attributes['roles'])) {
            $roles = $attributes['roles'];
        }
        unset($attributes['roles']);

        if (isset($roles)) {
            $user->roles()->sync($roles);
        }

        if (isset($attributes['subscriptions'])) {
            $subscriptions = $attributes['subscriptions'];
        }
        unset($attributes['subscriptions']);

        if (isset($subscriptions)) {
            $user->subscriptions()->sync($subscriptions);
        }

        $user->update($attributes);

        return back()->with('success', 'User Profile Updated!');
    }

    public function destroy(User $user)
    {
        // dd($teacher->course->all());
        $user->delete();
        Storage::disk('public')->deleteDirectory('assets/app/images/users/id_' . $user['id']);

        return redirect('/admin/dashboard/users')->with('success', 'User Deleted!');
    }

    public function setupIntent(){
        // dd(Auth::user()->createSetupIntent());
        return Inertia::render('Public/SetupIntent',[
            'intent'=> Auth::user()->createSetupIntent()
        ]);
    }

    protected function validateUser( ? User $user = null) : array
    {
        $user ??= new User();

        return request()->validate(
            [
                'first_name' => 'required|min:3|max:50',
                'last_name' => 'required|max:50',
                'avatar' => $user->exists ? 'nullable' : 'nullable|mimes:jpeg,png |max:2096',
                'dob' => 'required|max:50',
                'lead_id'=>'nullable|numeric',
                'roles' => [Auth::guard('web')->user()->can('admin') ? 'nullable' : 'exclude'],
                'subscriptions' => [$user->exists ? Rule::excludeIf(count(request()->input('roles'))>1) : Rule::excludeIf(count(request()->input('roles'))>0) , 'nullable'],
                'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'about' => 'nullable|max:500',
                'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user)],
                'password' => (request()->input('password') ?? false || !$user->exists) ? 'required|confirmed|min:6' : 'exclude',
                'tac' => 'required|accepted',
            ],
            [
                'dob' => 'Date of birth required',
                'roles' => 'Please select roles',
                'phone_number' => 'Enter a valid Phone number with country code',
                'about.max' => 'About text exceeds limit 500',
                'avatar' => 'Upload Profile image as jpg/png format with size less than 2MB',
                'tac' => 'Please agree to the Terms and Conditions!',
            ]
        );
    }
}
