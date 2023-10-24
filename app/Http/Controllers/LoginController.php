<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(){
        return Inertia::render('Public/Auth/Login');
    }

    public function auth(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        if(Auth::attempt($attributes)){
            $request->session()->regenerate();

            $cartItems = \Cart::getContent();

            if(Auth::check()) {
                \Cart::session(Auth::user()->id);
                foreach($cartItems as $row) {
                    \Cart::add(
                        array(
                            'id' => $row['id'],
                            'name' => $row['name'],
                            'price' => $row['price'],
                            'quantity' => $row['quantity'],
                            'attributes'=>$row['attributes'],
                            'associatedModel' => $row['associatedModel']
                        )
                    );
                }
            }
            if(Auth::user()->can('admin')){
                return redirect()->intended('admin/dashboard')->with('success','You are logged-in');

            }
            return redirect()->intended('/')->with('success','You are logged-in');
        }

        

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }


    public function create()
    {
        return Inertia::render('Public/Auth/Register');
    }

    public function store(Request $request)
    {

            $attributes = $this->validateUser();

            $user = User::create($attributes);
            $user->roles()->attach([1]);
            $user->save();

            Auth::login($user);
            return redirect('/')->with('success', 'Your account has been created.');
    }

        protected function validateUser(?User $user = null): array
    {
        $user ??= new User();

        return request()->validate([
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|max:50',
            // 'avatar' => 'nullable|mimes:jpeg,png |max:2096',
            'email' => ['required','email', Rule::unique('users', 'email')->ignore($user)],
            // 'gender' => 'nullable',
            // 'birthday' => 'nullable',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'password' => (request()->input('password') ?? false || !$user->exists ) ? 'required|confirmed|min:6': 'nullable',
            'tac'=>'required|accepted'
        ],[
            'avatar' => 'Upload Profile image as jpg/png format with size less than 2MB',
            'tac'=>'Please agree to the Terms and Conditions!'
        ]);
    }
}
