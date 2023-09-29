<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class TrainerController extends Controller
{
    //
    public function index()
    {
        // dd(User::all());
        return Inertia::render('Admin/Dashboard/Trainers/Index', [
            'users' => User::filter(
                request(['search', 'dateStart', 'dateEnd', 'sortBy', 'roles'])
            )->whereHas(
                'roles', function($q){
                    $q->where('name', ['Trainer','Administrator']);
                })
                ->with(['roles'])->paginate(3)->withQueryString(),
            'filters' => Request::only(['search', 'sortBy', 'dateStart', 'dateEnd', 'roles']),
            'roles' => Role::whereNotIn('value', ['USER_ROLE','TRAINER_ROLE'])->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Dashboard/Trainers/Create', [
            'roles' => Role::whereNotIn('value', ['USER_ROLE','TRAINER_ROLE'])->get(),
        ]);
    }
}
