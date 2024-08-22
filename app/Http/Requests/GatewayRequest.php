<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\ValidIPv4AddressRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class GatewayRequest extends FormRequest
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
        $objectId = $this->route('id');
        return [
            'name' => 'max:255',
            'serial_number' => ['required', Rule::unique('gateways', 'serial_number')->ignore($objectId)],
            'IPv4_address' => [new ValidIPv4AddressRule()],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(
            response()->json(
                [
                    'success' => false,
                    'message' => 'Validation errors',
                    'data' => $errors,
                ],
                Response::HTTP_BAD_REQUEST,
            ),
        );
    }
}
