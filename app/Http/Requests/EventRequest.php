<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'status' => 'sometimes|string|in:draft,published,cancelled',
            'attendees_limit' => 'nullable|integer|min:1',
            'is_featured' => 'boolean'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'description.required' => 'The description field is required.',
            'date.required' => 'The date field is required.',
            'location.required' => 'The location field is required.',
            'attendees_limit.min' => 'Attendees limit must be at least 1.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => 'event title',
            'description' => 'event description',
            'date' => 'event date',
            'location' => 'event location',
            'status' => 'event status',
            'attendees_limit' => 'maximum attendees',
            'is_featured' => 'featured event status'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('date') && $this->date < now()->format('Y-m-d')) {
                $validator->errors()->add('date', 'The date must be a future date.');
            }
        });
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        if ($this->filled('date')) {
            $data['date'] = $data['date'];
        }

        if ($this->has('is_featured')) {
            $data['is_featured'] = (bool) $this->is_featured;
        }

        return $data;
    }

    // Dashboard specific methods
    public function getDashboardData()
    {
        return [
            'title' => $this->title(),
            'description' => $this->description(),
            'date' => $this->getEventDate(),
            'location' => $this->location(),
            'status' => $this->status(),
            'attendees_limit' => $this->attendeesLimit(),
            'is_featured' => $this->isFeatured()
        ];
    }

    public function getEventDate()
    {
        return $this->filled('date') ? $this->date : null;
    }

    public function title()
    {
        return $this->title;
    }

    public function description()
    {
        return $this->description;
    }

    public function location()
    {
        return $this->location;
    }

    public function status()
    {
        return $this->status ?? 'draft';
    }

    public function attendeesLimit()
    {
        return $this->attendees_limit;
    }

    public function isFeatured()
    {
        return (bool) ($this->is_featured ?? false);
    }
}