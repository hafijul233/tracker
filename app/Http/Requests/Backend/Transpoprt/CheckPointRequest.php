<?php

namespace App\Http\Requests\Backend\Transpoprt;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @class CheckPointRequest
 * @package App\Http\Requests\Backend\Transport
 */
class CheckPointRequest extends FormRequest
{
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
