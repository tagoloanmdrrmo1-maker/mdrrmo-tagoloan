<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->user_id : null;

        return [
            'username' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[a-zA-Z][a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};\':\"\\|,.<>\/?]*$/',
                Rule::unique('users', 'username')->ignore($userId, 'user_id')
            ],
            'first_name' => [
                'required',
                'string',
                'max:255',
                'min:2',
                'regex:/^[A-Za-z\s]+$/'
            ],
            'middle_name' => [
                'nullable',
                'string',
                'max:255',
                'min:2',
                'regex:/^[A-Za-z\s]+$/'
            ],
            'last_name' => [
                'required',
                'string',
                'max:255',
                'min:2',
                'regex:/^[A-Za-z\s]+$/'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId, 'user_id')
            ],
            'contact_num' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s()]+$/'
            ],
            'password' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'string',
                'min:8'
            ],
            'role' => [
                'required',
                'string',
                'max:255',
                Rule::in(['Admin', 'Staff', 'User'])
            ],
            'status' => [
                'nullable',
                'string',
                Rule::in(['active', 'inactive'])
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'username.min' => 'Username must be at least 3 characters long.',
            'username.max' => 'Username must not exceed 255 characters.',
            'username.regex' => 'Username must start with a letter and can only contain letters, numbers, and special characters.',
            'username.unique' => 'This username is already taken. Please choose a different username.',
            
            'first_name.required' => 'First name is required.',
            'first_name.min' => 'First name must be at least 2 characters long.',
            'first_name.max' => 'First name must not exceed 255 characters.',
            'first_name.regex' => 'First name may only contain letters and spaces.',
            
            'middle_name.required' => 'Middle name is required.',
            'middle_name.min' => 'Middle name must be at least 2 characters long.',
            'middle_name.max' => 'Middle name must not exceed 255 characters.',
            'middle_name.regex' => 'Middle name may only contain letters and spaces.',
            
            'last_name.required' => 'Last name is required.',
            'last_name.min' => 'Last name must be at least 2 characters long.',
            'last_name.max' => 'Last name must not exceed 255 characters.',
            'last_name.regex' => 'Last name may only contain letters and spaces.',
            
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email must not exceed 255 characters.',
            'email.unique' => 'This email is already registered. Please use a different email address.',
            
            'contact_num.required' => 'Contact number is required.',
            'contact_num.max' => 'Contact number must not exceed 20 characters.',
            'contact_num.regex' => 'Please enter a valid contact number.',
            
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            
            'role.required' => 'Role is required.',
            'role.in' => 'Please select a valid role (Admin, Staff, or User).',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitize username: trim spaces, convert to lowercase
        if ($this->has('username')) {
            $username = trim($this->input('username'));
            $this->merge(['username' => $username]);
        }

        // Sanitize email: trim spaces, convert to lowercase
        if ($this->has('email')) {
            $email = trim(strtolower($this->input('email')));
            $this->merge(['email' => $email]);
        }
    }
}