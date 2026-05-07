<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatusUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'status'  => ['required', 'in:pending,enrolled,disapproved'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ];
    }
}