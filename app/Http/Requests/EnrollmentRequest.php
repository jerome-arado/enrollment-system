<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;  // Add this import

class EnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isStudent();
    }

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'min:2', 'max:255'],
            'age'             => [
                'required',
                'integer',
                'min:15',
                'max:80',
                // Custom rule: age must match the calculated age from birthdate
                function ($attribute, $value, $fail) {
                    $birthdate = $this->input('birthdate');
                    if ($birthdate) {
                        $calculatedAge = Carbon::parse($birthdate)->age;
                        if ((int)$value !== $calculatedAge) {
                            $fail('The age does not match the provided birthdate.');
                        }
                    }
                },
            ],
            'address'         => ['required', 'string', 'min:10', 'max:500'],
            'birthdate'       => ['required', 'date', 'before:today'],
            'course'          => ['required', 'in:BSIT,BSIS,BSCS'],
            'year'            => ['required', 'in:1st,2nd,3rd,4th'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'form137'         => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'birth_cert'      => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'good_moral'      => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'medical'         => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'id_picture'      => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ];
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
            'form137.required'         => 'Form 137 is required.',
            'form137.mimes'            => 'Form 137 must be a PDF, DOC, or DOCX file.',
            'birth_cert.required'      => 'Birth certificate is required.',
            'good_moral.required'      => 'Good moral certificate is required.',
            'medical.required'         => 'Medical certificate is required.',
            'id_picture.required'      => 'ID picture is required.',
            'id_picture.image'         => 'ID picture must be an image file.',
            // No custom message for the closure rule needed; the message is defined inside the closure.
        ];
    }
}