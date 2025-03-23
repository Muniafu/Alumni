<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'type' => 'required|string|in:full-time,part-time,contract,internship',
            'status' => 'required|string|in:active,closed,draft',
            'application_deadline' => 'required|date|after:today',
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => 'The job type must be one of the following: full-time, part-time, contract, internship.',
            'status.in' => 'The job status must be either active, closed, or draft.',
            'application_deadline.after' => 'The deadline must be a future date.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'job title',
            'company' => 'company name',
            'location' => 'job location',
            'salary' => 'salary',
            'type' => 'job type',
            'status' => 'job status',
            'application_deadline' => 'application deadline'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('salary') && $this->salary < 0) {
                $validator->errors()->add('salary', 'The salary must be at least 0.');
            }
        });
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);
        
        if ($this->filled('salary')) {
            $data['salary'] = (int) $data['salary'];
        }

        return $data;
    }

    // Helper methods for frontend data handling
    public function getJobFormData(): array
    {
        return [
            'title' => $this->title(),
            'description' => $this->description(),
            'company' => $this->company(),
            'location' => $this->location(),
            'salary' => $this->salary(),
            'type' => $this->type(),
            'status' => $this->status(),
            'application_deadline' => $this->application_deadline
        ];
    }

    public function getStatusOptions(): array
    {
        return ['active', 'closed', 'draft'];
    }

    public function getTypeOptions(): array
    {
        return ['full-time', 'part-time', 'contract', 'internship'];
    }

    // Original getter methods
    public function salary()
    {
        return $this->filled('salary') ? (int) $this->salary : null;
    }

    public function type()
    {
        return $this->type;
    }

    public function status()
    {
        return $this->status;
    }

    public function company()
    {
        return $this->company;
    }

    public function location()
    {
        return $this->location;
    }

    public function description()
    {
        return $this->description;
    }

    public function title()
    {
        return $this->title;
    }

}