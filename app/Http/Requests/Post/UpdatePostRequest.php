<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => 'sometimes|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,pdf,doc,docx|max:10240'
        ];
    }
}