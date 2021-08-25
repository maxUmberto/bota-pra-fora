<?php

namespace App\Http\Requests\VentComments;

use Illuminate\Foundation\Http\FormRequest;

class CreateVentCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->vent->allow_comments;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comment_content' => 'required|max:500',
        ];
    }
}
