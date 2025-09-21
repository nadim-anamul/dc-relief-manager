<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZillaRequest extends FormRequest
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
                'min:2',
                'unique:zillas,name,' . $this->route('zilla'),
            ],
            'name_bn' => [
                'required',
                'string',
                'max:255',
                'min:2',
            ],
            'code' => [
                'required',
                'string',
                'max:10',
                'min:2',
                'unique:zillas,code,' . $this->route('zilla'),
                'regex:/^[A-Z0-9]+$/',
            ],
            'is_active' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'District name is required.',
            'name.min' => 'District name must be at least 2 characters.',
            'name.max' => 'District name cannot exceed 255 characters.',
            'name.unique' => 'A district with this name already exists.',
            
            'name_bn.required' => 'District name in Bengali is required.',
            'name_bn.min' => 'District name in Bengali must be at least 2 characters.',
            'name_bn.max' => 'District name in Bengali cannot exceed 255 characters.',
            
            'code.required' => 'District code is required.',
            'code.min' => 'District code must be at least 2 characters.',
            'code.max' => 'District code cannot exceed 10 characters.',
            'code.unique' => 'A district with this code already exists.',
            'code.regex' => 'District code must contain only uppercase letters and numbers.',
            
            'is_active.boolean' => 'Active status must be true or false.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name_bn' => 'district name in Bengali',
            'code' => 'district code',
            'is_active' => 'active status',
        ];
    }
}