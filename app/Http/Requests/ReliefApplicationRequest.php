<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReliefApplicationRequest extends FormRequest
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
        $rules = [
            'application_type' => [
                'required',
                'in:individual,organization',
            ],
            'applicant_nid' => [
                'nullable',
                'string',
                'max:20',
                'min:10',
                'regex:/^[0-9]{10,20}$/',
            ],
            'organization_name' => [
                'required_if:application_type,organization',
                'nullable',
                'string',
                'max:255',
                'min:2',
            ],
            'organization_type_id' => [
                'nullable',
                'exists:organization_types,id',
            ],
            'date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'zilla_id' => [
                'required',
                'exists:zillas,id',
            ],
            'upazila_id' => [
                'required',
                'exists:upazilas,id',
                Rule::exists('upazilas', 'id')->where('zilla_id', $this->zilla_id),
            ],
            'union_id' => [
                'required',
                'exists:unions,id',
                Rule::exists('unions', 'id')->where('upazila_id', $this->upazila_id),
            ],
            'ward_id' => [
                'nullable',
                'exists:wards,id',
                Rule::exists('wards', 'id')->where('union_id', $this->union_id),
            ],
            'subject' => [
                'required',
                'string',
                'max:500',
            ],
            'applicant_name' => [
                'required',
                'string',
                'max:255',
                'min:2',
            ],
            'applicant_designation' => [
                'nullable',
                'string',
                'max:255',
            ],
            'applicant_phone' => [
                'required',
                'string',
                'regex:/^(\+88)?01[3-9]\d{8}$/',
            ],
            'applicant_address' => [
                'required',
                'string',
                'max:1000',
                'min:10',
            ],
            'organization_address' => [
                'required_if:application_type,organization',
                'nullable',
                'string',
                'max:1000',
                'min:10',
            ],
            'amount_requested' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999999.99',
            ],
            'relief_type_id' => [
                'required',
                'exists:relief_types,id',
            ],
            'project_id' => [
                'required',
                'exists:projects,id',
            ],
            'details' => [
                'required',
                'string',
                'min:50',
                'max:2000',
            ],
            'general_comment' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'application_file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240', // 10MB
            ],
        ];

        // For admin approval/rejection
        if ($this->isMethod('PATCH') && $this->route('relief_application')) {
            $rules = array_merge($rules, [
                'status' => [
                    'required',
                    'in:pending,approved,rejected',
                ],
                'approved_amount' => [
                    'required_if:status,approved',
                    'nullable',
                    'numeric',
                    'min:0',
                    'max:10000000',
                ],
                'project_id' => [
                    'required_if:status,approved',
                    'nullable',
                    'exists:projects,id',
                ],
                'admin_remarks' => [
                    'required_if:status,rejected',
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ]);
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'application_type.required' => 'Please select an application type.',
            'application_type.in' => 'Application type must be individual or organization.',
            
            'applicant_nid.regex' => 'Please enter a valid NID number (10-20 digits).',
            'applicant_nid.min' => 'NID must be at least 10 digits.',
            'applicant_nid.max' => 'NID cannot exceed 20 digits.',
            
            'organization_name.required_if' => 'Organization name is required for organization applications.',
            'organization_name.min' => 'Organization name must be at least 2 characters.',
            'organization_name.max' => 'Organization name cannot exceed 255 characters.',
            
            'organization_type_id.exists' => 'Selected organization type is invalid.',
            
            'date.required' => 'Application date is required.',
            'date.date' => 'Please enter a valid date.',
            'date.before_or_equal' => 'Application date cannot be in the future.',
            
            'zilla_id.required' => 'Please select a district.',
            'zilla_id.exists' => 'Selected district is invalid.',
            
            'upazila_id.required' => 'Please select an upazila.',
            'upazila_id.exists' => 'Selected upazila is invalid or does not belong to the selected district.',
            
            'union_id.required' => 'Please select a union.',
            'union_id.exists' => 'Selected union is invalid or does not belong to the selected upazila.',
            
            'ward_id.required' => 'Please select a ward.',
            'ward_id.exists' => 'Selected ward is invalid or does not belong to the selected union.',
            
            'subject.required' => 'Subject is required.',
            'subject.max' => 'Subject cannot exceed 500 characters.',
            
            'applicant_name.required' => 'Applicant name is required.',
            'applicant_name.min' => 'Applicant name must be at least 2 characters.',
            'applicant_name.max' => 'Applicant name cannot exceed 255 characters.',
            
            'applicant_phone.required' => 'Phone number is required.',
            'applicant_phone.regex' => 'Please enter a valid Bangladeshi mobile number.',
            
            'applicant_address.required' => 'Applicant address is required.',
            'applicant_address.min' => 'Address must be at least 10 characters.',
            'applicant_address.max' => 'Address cannot exceed 1000 characters.',
            
            'organization_address.required_if' => 'Organization address is required for organization applications.',
            'organization_address.min' => 'Organization address must be at least 10 characters.',
            'organization_address.max' => 'Organization address cannot exceed 1000 characters.',
            
            'amount_requested.required' => 'Amount requested is required.',
            'amount_requested.numeric' => 'Amount must be a valid number.',
            'amount_requested.min' => 'Amount must be greater than 0.',
            'amount_requested.max' => 'Amount cannot exceed 999,999,999.99.',
            
            'details.required' => 'Details are required.',
            'details.min' => 'Details must be at least 50 characters.',
            'details.max' => 'Details cannot exceed 2000 characters.',
            
            'general_comment.max' => 'General comment cannot exceed 2000 characters.',
            
            'application_file.file' => 'Please upload a valid file.',
            'application_file.mimes' => 'File must be a PDF, DOC, DOCX, JPG, JPEG, or PNG.',
            'application_file.max' => 'File size cannot exceed 10MB.',
            
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be pending, approved, or rejected.',
            
            'approved_amount.required_if' => 'Approved amount is required when approving.',
            'approved_amount.numeric' => 'Approved amount must be a valid number.',
            'approved_amount.min' => 'Approved amount cannot be negative.',
            'approved_amount.max' => 'Approved amount cannot exceed ৳1,00,00,000.',
            
            'relief_type_id.required' => 'Relief type selection is required.',
            'relief_type_id.exists' => 'Selected relief type is invalid.',
            
            'project_id.required_if' => 'Project selection is required when approving.',
            'project_id.required' => 'Project selection is required.',
            'project_id.exists' => 'Selected project is invalid.',
            
            'admin_remarks.required_if' => 'Admin remarks are required when rejecting.',
            'admin_remarks.max' => 'Admin remarks cannot exceed 1000 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'application_type' => 'application type',
            'applicant_nid' => 'NID',
            'organization_name' => 'organization name',
            'organization_type_id' => 'organization type',
            'zilla_id' => 'district',
            'upazila_id' => 'upazila',
            'union_id' => 'union',
            'ward_id' => 'ward',
            'applicant_name' => 'applicant name',
            'applicant_designation' => 'applicant designation',
            'applicant_phone' => 'phone number',
            'applicant_address' => 'applicant address',
            'organization_address' => 'organization address',
            'amount_requested' => 'amount requested',
            'relief_type_id' => 'relief type',
            'project_id' => 'project',
            'application_file' => 'application file',
            'approved_amount' => 'approved amount',
            'admin_remarks' => 'admin remarks',
        ];
    }
}