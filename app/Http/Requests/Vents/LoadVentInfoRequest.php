<?php

namespace App\Http\Requests\Vents;

use Illuminate\Foundation\Http\FormRequest;

class LoadVentInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     * by checking if hes trying to access a vent that belongs
     * to him
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->vent->user_id == auth()->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
