<?php

namespace Modules\News\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNews extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'    => 'required',
            'desc'     => 'string|nullable',
            'link'     => 'string|nullable',
            'section_id'     => 'required',
            'image'    => 'required|mimes:jpeg,png,jpg,gif,svg',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
