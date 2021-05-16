<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopicUpdateRequest extends FormRequest
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
                'min:2', 'max:255'
            ],
            'body' => [
                'min:5', 'max:5000'
            ],
            'image' => [
                'image', 'max:2000'
            ],
        ];
    }
}
