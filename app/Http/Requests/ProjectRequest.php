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
                'min:3',
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
            'budget' => [
                'required',
                'numeric',
                'min:10000',
                'max:1000000000',
            ],
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
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
            
            'budget.required' => 'Budget is required.',
            'budget.numeric' => 'Budget must be a valid number.',
            'budget.min' => 'Budget must be at least ৳10,000.',
            'budget.max' => 'Budget cannot exceed ৳1,00,00,00,000.',
            
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Please enter a valid start date.',
            'start_date.after_or_equal' => 'Start date cannot be in the past.',
            
            'end_date.required' => 'End date is required.',
            'end_date.date' => 'Please enter a valid end date.',
            'end_date.after' => 'End date must be after the start date.',
            
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
            'start_date' => 'start date',
            'end_date' => 'end date',
        ];
    }
}