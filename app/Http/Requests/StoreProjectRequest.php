<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by the Controller/Policy
    }

    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'event_id' => 'required|exists:events,id',
            'advisor_id' => 'nullable|exists:users,id',
            'category' => 'required|exists:categories,code',
            'description' => 'required|string|min:10',
            'repository_url' => 'nullable|url'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título del proyecto es obligatorio.',
            'event_id.required' => 'Debes seleccionar un evento.',
            'category.required' => 'Debes seleccionar una categoría.',
            'description.min' => 'La descripción debe tener al menos 10 caracteres.',
        ];
    }
}
