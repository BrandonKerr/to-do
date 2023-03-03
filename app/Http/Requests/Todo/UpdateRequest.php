<?php

namespace App\Http\Requests\Todo;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        $todo = $this->route("todo");

        return $this->user()->can("update", $todo);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            "title" => "required|max:255",
            "is_complete" => "boolean",
        ];
    }

    public function messages() {
        return [
            "title.required" => __("todo.title_required"),
            "title.max" => __("todo.title_max"),
        ];
    }
}
