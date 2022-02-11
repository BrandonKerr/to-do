<?php

namespace App\Http\Requests\Checklist;

use App\Models\Checklist;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $checklist = Checklist::findOrFail($this->route('checklist')->id);
        return $this->user()->can('update', $checklist);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255'
        ];
    }
}
