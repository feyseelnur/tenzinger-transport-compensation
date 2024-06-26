<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GenerateReportRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'year' => 'required|string|max:4',
            'debug' => 'boolean',
            'wait_for_result' => 'boolean',
        ];
    }

    public function failedValidation(Validator $validator):void
    {
        throw new HttpResponseException(response()->json([
            'data' => [
                'errors' => $validator->errors(),
            ]
        ], 422));
    }
}
