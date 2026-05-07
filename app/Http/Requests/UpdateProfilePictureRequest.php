<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilePictureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'profile_picture' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'profile_picture.required' => 'Please choose an image to upload.',
            'profile_picture.image'    => 'The file must be an image.',
            'profile_picture.mimes'    => 'Only JPEG, PNG, and WebP images are allowed.',
            'profile_picture.max'      => 'Profile picture must not exceed 2MB.',
        ];
    }
}