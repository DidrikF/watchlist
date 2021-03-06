<?php

namespace App\Http\Requests;

use App\Traits\SanitizedRequest;

use Illuminate\Foundation\Http\FormRequest;

class WatchlistRequest extends FormRequest
{
    use SanitizedRequest;
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

    //axios.put('/watchlist/' + this.watchlist.id, {name: this.title, description: this.description})

    public function rules()
    {

        

        return [
            'name' => 'required|max:50',
            'description' => 'required|max:1000',
        ];
    }
    
}
