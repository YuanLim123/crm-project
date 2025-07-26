<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\ProjectStatus;

class ProjectPostRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:128'],
            'description' => ['required', 'string'],
            'status' => ['required', new Enum(ProjectStatus::class)],
            'endDate' => ['required', 'date'],
            'client' => ['required', 'exists:clients,id'],
            'assignedUser' => ['required', 'exists:users,id'],
            'tasks' => ['array'],
            'tasks.*.id' => ['nullable'],
            'tasks.*.title' => ['required', 'string', 'max:128'],
            'tasks.*.description' => ['required', 'string', 'max:255'],
            'tasks.*.status' => ['required', new Enum(ProjectStatus::class)],
            'tasks.*.endDate' => ['required', 'date'],
            'tasks.*.user' => ['required', 'exists:users,id'],
        ];
    }
}
