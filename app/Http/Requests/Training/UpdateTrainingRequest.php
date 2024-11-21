<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'duration' => 'sometimes|string',
            'level' => 'sometimes|in:beginner,intermediate,advanced',
            'category_id' => 'sometimes|exists:categories,id',
            'video' => 'nullable|file|mimes:mp4,webm|max:102400'
        ];
    }
}