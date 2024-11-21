<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $this->user->id,
            'department' => 'sometimes|string|max:255',
            'position' => 'sometimes|string|max:255',
            'role' => 'sometimes|string|exists:roles,name'
        ];
    }
}