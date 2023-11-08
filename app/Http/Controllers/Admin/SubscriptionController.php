<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    public function index(){
        return Inertia::render('Admin/Dashboard/Subscriptions/Index', [
            'subscriptions' => Subscription::filter(
                request(['search', 'dateStart', 'dateEnd', 'sortBy', 'status'])
            )
                ->paginate(3)->withQueryString(),
            'filters' => Request::only(['search', 'sortBy', 'dateStart', 'dateEnd', 'status']),
        ]);
    }
}
