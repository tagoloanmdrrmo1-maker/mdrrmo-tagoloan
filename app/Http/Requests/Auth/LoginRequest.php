<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        // Sanitize username: trim spaces, remove dangerous chars
        if ($this->has('username')) {
            $rawUsername = (string) $this->input('username', '');
            $trimmed = trim($rawUsername);
            // Remove dangerous characters: < > = ' " ; ( ) / \
            $stripped = preg_replace('/[<>="\'();\/\\\\]/', '', $trimmed);
            $this->merge(['username' => $stripped]);
        }
    }

    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:255'
            ],
            'password' => [
                'required',
                'string',
                'min:8'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $username = (string) $this->input('username');
        $password = (string) $this->input('password');

        $attempted = Auth::attempt(['username' => $username, 'password' => $password], $this->boolean('remember'));

        if (! $attempted) {
            RateLimiter::hit($this->throttleKey());

            // Check if we're approaching the limit (3 attempts)
            $attempts = RateLimiter::attempts($this->throttleKey());
            if ($attempts >= 2) { // After 2 attempts, show warning
                throw ValidationException::withMessages([
                    'username' => 'LOCKED → Too many login attempts. Please try again in ' . RateLimiter::availableIn($this->throttleKey()) . ' seconds.',
                ]);
            } else {
                throw ValidationException::withMessages([
                    'username' => __('auth.failed'),
                ]);
            }
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'LOCKED → Too many login attempts. Please try again in ' . $seconds . ' seconds.',
        ]);
    }

    public function throttleKey(): string
    {
        $identifier = (string) ($this->input('email') ?: $this->input('username'));
        return Str::transliterate(Str::lower($identifier).'|'.$this->ip());
    }
}