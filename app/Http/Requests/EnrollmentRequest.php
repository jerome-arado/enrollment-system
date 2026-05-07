<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isStudent();
    }

    public function rules(): array
    {
        $rules = [
            'name'            => ['required', 'string', 'min:2', 'max:255'],
            'age'             => ['required', 'integer', 'min:15', 'max:80'],
            'address'         => ['required', 'string', 'min:10', 'max:500'],
            'birthdate'       => ['required', 'date', 'before:today'],
            'course'          => ['required', 'in:BSIT,BSIS,BSCS'],
            'year'            => ['required', 'in:1st,2nd,3rd,4th'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'Full name is required.',
            'age.required'             => 'Age is required.',
            'age.min'                  => 'Age must be at least 15.',
            'address.required'         => 'Address is required.',
            'address.min'              => 'Please provide a complete address.',
            'birthdate.required'       => 'Birthdate is required.',
            'birthdate.before'         => 'Birthdate must be in the past.',
            'course.required'          => 'Please select a course.',
            'course.in'                => 'Invalid course selected.',
            'year.required'            => 'Please select your year level.',
            'year.in'                  => 'Invalid year level selected.',
            'profile_picture.image'    => 'The file must be an image.',
            'profile_picture.mimes'    => 'Only JPEG, PNG, and WebP images are allowed.',
            'profile_picture.max'      => 'Profile picture must not exceed 2MB.',
        ];
    }
}