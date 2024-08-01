<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class PeripheralRequest extends FormRequest
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
            'UID' => 'numeric|min:1',
            'vendor' => 'required|string|min:2',
            'gateway_id' => [
                'integer',
                'exists:gateways,id',
                function ($attribute, $value, $fail) {
                    if ($value != null) {
                        $peripherals = \App\Models\Peripheral::where('gateway_id', $value)->get();
                        if (count($peripherals) > 10) {
                            $fail($attribute . " can't be associated with more than 10 gateways.");
                        }
                    }
                },
            ],
            'status' => [
                function ($attribute, $value, $fail) {
                    if (!in_array($value, ['online', 'offline'])) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
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
