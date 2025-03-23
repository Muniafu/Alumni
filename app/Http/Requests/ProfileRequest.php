<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;


class ProfileRequest extends FormRequest
{
    // Original authorization and validation methods remain the same
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user()->id,
            'password' => 'nullable|min:8|confirmed',
            'profile_image' => 'nullable|image|max:2048', // Allow profile image upload
            'phone' => 'nullable|string|max:20',          // Add phone field
            'bio' => 'nullable|string|max:500',           // Add bio field
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'The email has already been taken.',
            'profile_image.image' => 'The file must be an image.',
            'profile_image.max' => 'The image size must not exceed 2MB.',
            'phone.max' => 'The phone number is too long.',
            'bio.max' => 'The bio must not exceed 500 characters.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'full name',
            'profile_image' => 'profile picture',
            'phone' => 'phone number',
            'bio' => 'biography',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('password') && !Hash::check($this->password, $this->user()->password)) {
                $validator->errors()->add('password', 'The password is incorrect.');
            }
        });
    }
}