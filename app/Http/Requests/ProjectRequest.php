<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:projects,name,' . $this->route('project'),
            ],
            'economic_year_id' => [
                'required',
                'exists:economic_years,id',
            ],
            'relief_type_id' => [
                'required',
                'exists:relief_types,id',
            ],
            'allocated_amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999999.99',
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required.',
            'name.min' => 'Project name must be at least 3 characters.',
            'name.max' => 'Project name cannot exceed 255 characters.',
            'name.unique' => 'A project with this name already exists.',
            
            'economic_year_id.required' => 'Please select an economic year.',
            'economic_year_id.exists' => 'Selected economic year is invalid.',
            
            'relief_type_id.required' => 'Please select a relief type.',
            'relief_type_id.exists' => 'Selected relief type is invalid.',
            
            'allocated_amount.required' => 'Allocated amount is required.',
            'allocated_amount.numeric' => 'Allocated amount must be a valid number.',
            'allocated_amount.min' => 'Allocated amount must be greater than 0.',
            'allocated_amount.max' => 'Allocated amount cannot exceed 999,999,999.99.',
            
            'remarks.max' => 'Remarks cannot exceed 1000 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'economic_year_id' => 'economic year',
            'relief_type_id' => 'relief type',
            'allocated_amount' => 'allocated amount',
        ];
    }
}