<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $this->sanitize();

        return [
            //
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        foreach($input as $key => $data){
            $input[$key] = filter_var($data, FILTER_SANITIZE_STRING);
        }

        //$input['financialScore'] = filter_var($input['name'], FILTER_SANITIZE_STRING);
        //$input['cfScore'] = filter_var($input['description'], FILTER_SANITIZE_STRING);

        $this->replace($input);     
    }
}
