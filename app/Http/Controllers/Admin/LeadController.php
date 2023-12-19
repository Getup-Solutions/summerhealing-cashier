<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class LeadController extends Controller
{
    //
    public function index()
    {

        // dd(request(['search', 'dateStart', 'dateEnd', 'sortBy', 'privilage']));
        return Inertia::render('Admin/Dashboard/Leads/Index', [
            'leads' => Lead::filter(
                request(['search', 'dateStart', 'dateEnd', 'sortBy', 'privilage'])
            )
                ->with(['user'])->paginate(3)->withQueryString(),
            'filters' => Request::only(['search', 'sortBy', 'dateStart', 'dateEnd', 'privilage']),
            // 'roles' => Role::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Dashboard/Leads/Create');
    }

    public function store()
    {

        $attributes = $this->validateLead();

        Lead::create($attributes);

        if (Auth::guard('web')->check()) {
            if (Auth::user()->can('admin')) {
                return redirect('/admin/dashboard/leads')->with('success', 'Lead has been created.');
            }
            abort(403, 'Unauthorized action.');
        }
        abort(403, 'Unauthorized action.');

    }

    public function edit(Lead $lead)
    {
        return Inertia::render('Admin/Dashboard/Leads/Edit', [
            'lead' => $lead
        ]);
    }

    public function update(Lead $lead)
    {

        $attributes = $this->validateLead($lead);

        $lead->update($attributes);

        return back()->with('success', 'Lead Updated!');
    }

    public function destroy(Lead $lead)
    {

        $lead->delete();

        return redirect('/admin/dashboard/leads')->with('success', 'Lead Deleted!');
    }

    protected function validateLead( ? Lead $lead = null) : array
    {
        $lead ??= new Lead();

        return request()->validate(
            [
                'first_name' => 'required|min:3|max:50',
                'last_name' => 'required|max:50',
                'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'source' => 'nullable',
                'email' => ['required', 'email'],
                'message'=>'nullable'
            ],
            [
                'phone_number' => 'Enter a valid Phone number with country code',
            ]
        );
    }
}
