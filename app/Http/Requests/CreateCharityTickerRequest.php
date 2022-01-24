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
        $r = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email:rfc,dns',
            'charity_organization_id' => 'required|exists:charity_organizations,id',
            'donation_amount' => 'required|numeric|min:0.01|lte:total_donation_amount',
            'tick_frequency' => 'required|integer',
            'tick_frequency_unit' => ['required', Rule::in(['sec', 'mins', 'hours', 'days'])],
            'stripe_token' => 'required',
        ];
        if(config('timetogive.mode')=='countup'){
            $r['timer_expiry_timestamp'] = 'required_without:is_subscribed|date_format:Y/m/d H:i';
        } elseif (config('timetogive.mode')=='deposit') {
            $r['total_donation_amount'] = ['required','numeric','min:0.01', 'gte:donation_amount'];
            $r['timezone'] = ['required', Rule::in(timezone_identifiers_list())];
        }
        return $r;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $r = [
            'charity_organization_id.required' => 'Please choose an organization',
            'charity_organization_id.exists' => 'Please choose a valid organization',
            'donation_amount.numeric' => 'Please enter a valid donation amount',
            'donation_amount.min' => 'Minimum donation amount is 0.01',
            'donation_amount.lte' => 'Subdivided amount cannot be more than total donation amount',
            'tick_frequency.integer' => 'Please enter a valid numeric value',
            'stripe_token.required' => 'Please enter valid payment details',
        ];
        if(config('timetogive.mode')=='countup'){
            $r['timer_expiry_timestamp.required_without'] = 'Please choose expiry date.';
            $r['timer_expiry_timestamp.date_format'] =  'Please enter a valid format(YYYY/MM/DD HH:SS).';
        } elseif (config('timetogive.mode')=='deposit') {
            $r['total_donation_amount.numeric'] =  'Please enter a valid total donation amount';
            $r['total_donation_amount.min'] =  'Minimum total donation amount is 0.01';
            $r['total_donation_amount.gte'] =  'Cannot be less than "Give" subdivided amount';
        }
        return $r;
    }
}
