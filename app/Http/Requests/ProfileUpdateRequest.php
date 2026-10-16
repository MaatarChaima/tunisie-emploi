<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'website' => ['nullable', 'string', 'url', 'max:255'],
        ];

        if ($this->user()->isCandidate()) {
            $rules = array_merge($rules, [
                'birth_date' => ['required', 'date'],
                'city' => ['required', 'string', 'max:255'],
                'domain' => ['required', 'string', 'max:255'],
                'education_level' => ['required', 'string', 'max:255'],
                'experience_years' => ['required', 'integer', 'min:0'],
                'linkedin_url' => ['nullable', 'url', 'max:255'],
                'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            ]);
        } elseif ($this->user()->isCompany()) {
            $rules = array_merge($rules, [
                'sector' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'company_size' => ['required', 'string', 'max:255'],
                'tax_id' => ['required', 'string', 'max:255'],
                'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);
        }

        return $rules;
    }
}
