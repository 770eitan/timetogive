<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCharityTickerRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email:rfc,dns',
            'charity_organization_id' => 'required|exists:charity_organizations,id',
            'donation_amount' => 'required|numeric|min:0.01',
            'tick_frequency' => 'required|integer',
            'tick_frequency_unit' => ['required', Rule::in(['kdei', 'mins', 'hours', 'days'])],
            'stripe_token' => 'required',
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
            'charity_organization_id.required' => 'Please choose an organization',
            'charity_organization_id.exists' => 'Please choose a valid organization',
            'donation_amount.numeric' => 'Please enter a valid donation amount',
            'donation_amount.min' => 'Minimum donation amount is 0.01',
            'tick_frequency.integer' => 'Please enter a valid numeric value',
            'stripe_token.required' => 'Please enter a valid payment.',
        ];
    }
}
