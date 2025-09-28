<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            // Only letters and spaces
            'full_name'  => ['required', 'regex:/^[A-Za-z\s]+$/', 'unique:users,name'],
            'contact_no' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'role'       => ['required', 'string'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        $messages = [
            'full_name.required' => 'Full name is required.',
            'full_name.regex'    => 'Full name may only contain letters and spaces.',
            'full_name.unique'   => 'This full name is already taken.',

            'contact_no.required' => 'Contact number is required.',
            'contact_no.regex'    => 'Contact number must be 10 to 15 digits (0–9).',

            'email.required'      => 'Email is required.',
            'email.email'         => 'Please enter a valid email address.',
            'email.unique'        => 'This email is already registered.',

            'role.required'       => 'Role is required.',

            'password.required'   => 'Password is required.',
            'password.confirmed'  => 'Password confirmation does not match.',
        ];

        $request->validate($rules, $messages);

        $user = User::create([
            'name'        => $request->full_name,
            'contact_no'  => $request->contact_no,
            'role'        => $request->role,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Do NOT auto-login after registration; require user to login first
        // Auth::login($user);
        session()->forget('errors');

        return redirect()->route('login')->with('status', 'Registration successful. Please log in.');
    }
}
