<?php

namespace App\Http\Requests\Vents;

use Illuminate\Foundation\Http\FormRequest;

class CreateVentRequest extends FormRequest
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
            'vent_content'   => 'required|max:500',
            'allow_comments' => 'required|boolean',
        ];
    }
}
