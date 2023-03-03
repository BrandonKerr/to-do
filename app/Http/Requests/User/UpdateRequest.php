<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        $user = $this->route("user");

        return $this->user()->can("update", $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            "name" => "required|max:255",
            "email" => "required|email",
            "is_admin" => "boolean",
        ];
    }

    public function messages() {
        return [
            "name.required" => __("user.name_required"),
            "name.max" => __("user.name_max"),
            "email.required" => __("user.email_required"),
            "email.email" => __("user.email_email"),
        ];
    }
}
