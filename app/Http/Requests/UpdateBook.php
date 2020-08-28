<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBook extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'             => 'required|numeric',
            'writer_id'      => 'required|numeric',
            'category_id'    => 'required|numeric',
            'published_date' => 'required|date_format:Y-m-d',
            'synopsis'       => 'required|min:100',
            'title'          => 'required|min:5'
        ];
    }
}
