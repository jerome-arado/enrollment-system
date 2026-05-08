<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isStudent();
    }

    public function rules(): array
    {
        return [
            'label'    => ['required', 'string', 'max:100'],
            'document' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120',   // 5 MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'label.required'    => 'Please enter a label for this document.',
            'document.required' => 'Please choose a file to upload.',
            'document.file'     => 'The upload must be a file.',
            'document.mimes'    => 'Only PDF, DOC, and DOCX files are accepted.',
            'document.max'      => 'File size must not exceed 5 MB.',
        ];
    }
}