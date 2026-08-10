<?php

namespace App\Http\Requests;

use App\Services\TeamMemberService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InviteTeamMemberRequest extends FormRequest
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
            'email' => ['required', 'email'],
            // Single source of truth for the role names (admin|finance|viewer).
            'role'  => ['required', Rule::in(TeamMemberService::ROLES)],
        ];
    }
}
