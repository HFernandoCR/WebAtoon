<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'score_document' => 'required|numeric|min:0|max:20',
            'score_presentation' => 'required|numeric|min:0|max:30',
            'score_demo' => 'required|numeric|min:0|max:50',
            'feedback' => 'required|string|min:10'
        ];
    }
}
