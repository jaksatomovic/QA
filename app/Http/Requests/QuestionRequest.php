<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
            'title' => [
                'required', 'min:5', 'max:255'
            ],
            'body' => [
                'required', 'min:5', 'max:5000'
            ],
            'topics' => [
                'required', 'array', 'min:1', 'max:5'
            ],
        ];
    }
}
