<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:155'],
            'last_name'  => ['required', 'string', 'max:100'],
            // The email may stay the same (the current user's row is ignored).
            'email'      => ['required', 'email', 'max:155', Rule::unique('users', 'email')->ignore($this->user()?->id)],
            'phoneno'    => ['required', 'string', 'max:100'],
        ];
    }
}
