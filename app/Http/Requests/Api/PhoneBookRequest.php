<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class PhoneBookRequest extends FormRequest
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
            'first_name' => 'required|string|max:250',
            'last_name' => 'nullable|string|max:250',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'country_code' => 'nullable|valid_country_code',
            'timezone' => 'nullable|valid_timezone',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "success" => false,
            "type" => 'validation',
            'errors' => $validator->errors()
        ])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    public function withValidator($validator): void
    {
        $validator->addExtension('valid_country_code', function ($attribute, $value, $parameters, $validator) {
            $response = Http::get('http://country.io/names.json');
            $countryCodes = array_keys($response->json());

            return in_array($value, $countryCodes);
        });

        $validator->addExtension('valid_timezone', function ($attribute, $value, $parameters, $validator) {
            $response = Http::get('http://worldtimeapi.org/api/timezone');
            $timezones = $response->json();

            return in_array($value, $timezones);
        });
    }
}
