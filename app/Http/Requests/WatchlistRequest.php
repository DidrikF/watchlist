<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WatchlistRequest extends FormRequest
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
            //
        ];
    }
    /*
    public function sanitate()
    {
        htmlentities(); //Remeber to allways definde UTF-8 encoding, when you are working with that kind of strings. 
        htmlspecialchars();
    }
    */
}
