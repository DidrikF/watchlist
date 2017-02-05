<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest_old extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //authorized in middleware
    }

    private $validDataId = ['p', 'y', 'd', 't8', 'm4', 'g3', 's6', 'w', 'j1', 'v', 'e', 'b4', 'j4', 'p5', 'p6', 'r', 'r5', 's7'];

    /*
    public function validator(ValidationService $service)
    {
        $validator = $service->getValidator($this->input());
        $validator->after(function() use ($validator){
            //validate conditions
            //for each failure add error message
            //this is done after dataId is confirmed to be valid

            //$this->conditions;

            $validator->errors()->add('field', 'new error message');


        });
    }
    */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        /*
            Data to be validated:
            this.ticker, //exists at yahoo
            {name: this.name, //required|max:50|
            description: this.description, //max|1000
            conditions: this.conditions} //json

            Do I need a custom validator here?
            {dataName: 
            this.dataList[this.dataId],
            dataId: this.dataId, 
            comparisonOperator: this.selectedComparisonOperator, 
            dataValue: this.dataValue}

        */
        return [
            'name' => 'required|max:50',
            'description' => 'max:1000',
            'conditions' => 'json'
            //don't know about these
            'conditions.dataId' =>  'required|in:'.impode(',', $this->validDataId),
            'conditions.comparisonOperator' => 'required|in:<,>',
            'conditions.dataValue' => 'required|numeric', 
        ];
    }

    //after validation hook... ???
    private function conditionIsTriggered()
    {

    }
}
