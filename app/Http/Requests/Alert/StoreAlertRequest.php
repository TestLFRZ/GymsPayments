<?php

namespace App\Http\Requests\Alert;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'member_id' => ['required', 'integer', 'exists:members,id'],
            'subscription_id' => ['nullable', 'integer', 'exists:member_plan_subscriptions,id'],
            'type' => ['required', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'scheduled_for' => ['required', 'date'],
            'delivery_channel' => ['nullable', 'string', 'max:50'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
