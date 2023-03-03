<?php

namespace App\Http\Requests\Todo;

use App\Models\Todo;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        $checklist = $this->route("checklist");

        return $this->user()->can("create", Todo::class) && $this->user()->can("update", $checklist);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            "title" => "required|max:255",
        ];
    }

    public function messages() {
        return [
            "title.required" => __("todo.title_required"),
            "title.max" => __("todo.title_max"),
        ];
    }
}
