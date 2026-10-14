<?php

namespace App\Http\Requests\Centcoin;

use Illuminate\Foundation\Http\FormRequest;

class CentcoinStoreRequest extends FormRequest
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
            'type_id' => 'required|string|max:11',
            'description' => 'required|max:255|string',
            'quantity' => 'required|integer',
            'total' => 'required|integer',
            'user_id'=>'required|integer',
        ];
    }
}
