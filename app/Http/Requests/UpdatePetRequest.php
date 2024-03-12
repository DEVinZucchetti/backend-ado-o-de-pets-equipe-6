<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePetRequest extends FormRequest
{

    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:150',
            'age' => 'int',
            'weight' => 'numeric',
            'size' => 'string|in:SMALL,MEDIUM,LARGE,EXTRA_LARGE', // melhorar validacao para enum
            'race_id' => 'int',
            'specie_id' => 'int',
            'client_id' => 'int'
        ];
    }
}
