<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Request;

use GuzzleHttp\Client;

class WatchlistItemRequest extends FormRequest
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

    //let itemToAdd = {name: company.name, ticker: company.symbol, exchange: company.exch, industry: null};
    public function rules()
    {

        $this->sanitize();

        return [
            'name' => 'required|max:50',
            'exchange' => 'required',
            'ticker' => 'bail|required',
        ];
    }


    /*
        extra vlaidation rules:
        ticker must exist at yahoo
        exchange must exist at yahoo

        screw industry
    */

         public function validator($factory)
    {
        $validator = $factory->make(
            $this->all(), $this->container->call([$this, 'rules']), $this->messages(), $this->attributes()
        );

        $validator->after(function($validator) {
            $ticker = Request::input('ticker');
            $exchange = Request::input('exchange')
            $url = "http://finance.yahoo.com/d/quotes.csv?s={$ticker}&f=px";

            $client = new Client;
            //send request
            $response = $client->request('GET', $url)->getBody();
            $values = str_getcsv($response, ',', '"')

            if(!is_numeric($values[0])){
                $validator->errors()->add('ticker', 'Ticker does not exist');
            }
            if($values[1] != $exchange){
                $validator->errors()->add('exchange', 'Exchange does not exist or is invalid');
            }
        });

        return $validator;
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
