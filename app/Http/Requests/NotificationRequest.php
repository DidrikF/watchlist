<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Request;

use GuzzleHttp\Client;

class NotificationRequest extends FormRequest
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

        $this->sanitize();
        
        return [
            'name' => 'required|max:50',
            'description' => 'max:250',
            'conditions' => 'bail|required' //need to validate the conditions... that they are not triggered and not repeting
        ];
    }
    
    public function validator($factory)
    {
        $validator = $factory->make(
            $this->all(), $this->container->call([$this, 'rules']), $this->messages(), $this->attributes()
        );

        $validator->after(function($validator) {

            $validDataId = ['p', 'y', 'd', 't8', 'm4', 'g3', 's6', 'w', 'j1', 'v', 'e', 'b4', 'j4', 'p5', 'p6', 'r', 'r5', 's7'];
            $validComparisonOperators = ["<", ">"];
            $conditions = Request::input('conditions');
            $validConditions = [];
            $dataIds = [];
            $dataIdString = '';
            $client = new Client;
            $url = Request::url();
            $tempArr = explode('/', $url);
            $ticker = end($tempArr);
            $teller = 0;
            foreach($conditions as $condition){
                if(!in_array($condition['dataId'], $validDataId)){
                    $validator->errors()->add($condition['dataId'], 'Not supported');
                    continue;
                }//DataId is supported
                if(in_array($condition['dataId'], $dataIds)){
                    $validator->errors()->add($condition['dataId'], 'Duplicate condition');
                    $dataIdremoveKey = array_search($condition['dataId'], $dataIds); //if duplicates, that error message
                    unset($dataIds[$dataIdremoveKey]);                               //takes presidence.
                    $validConditionRemoveKey = array_search($condition, $validConditions);
                    unset($validConditions[$validConditionRemoveKey]);
                    continue;
                }//Not duplicate
                if(!isset($condition['dataId']) || !isset($condition['comparisonOperator']) || !isset($condition['dataValue'])){
                    if($condition['dataId']){
                        $validator->errors()->add($condition['dataId'], 'Missing field(s)');
                    }
                    $validator->errors()->add('general', 'Condition(s) missing field(s)');
                    continue;
                }//all fields present
                if(!in_array($condition['comparisonOperator'], $validComparisonOperators)){
                    $validator->errors()->add($condition['dataId'], 'Invalid comparison operator');
                    continue;
                }
                if(!is_numeric($condition['dataValue'])){
                    $validator->errors()->add($condition['dataId'], 'Value not numeric');
                    continue;
                }
                array_push($validConditions, $condition);
                $dataIds[] = $condition['dataId']; //only dataIds with all fields, is supported and no duplicate
            }
            
            $dataIdString = implode('', $dataIds);
            $yahooUrl = "http://finance.yahoo.com/d/quotes.csv?s={$ticker}&f={$dataIdString}";
            $response = $client->request('GET', $yahooUrl)->getBody();
            $currentCompanyDataArray = str_getcsv($response, ',', '"'); //working

            foreach($validConditions as $condition){

                switch($condition['comparisonOperator'])
                {
                    case "<":
                        if($currentCompanyDataArray[$teller] < $condition['dataValue']) {
                            $validator->errors()->add($condition['dataId'], 'Allready true');
                        }
                        break;
                    case ">":
                        if($currentCompanyDataArray[$teller] > $condition['dataValue']) {
                            $validator->errors()->add($condition['dataId'], 'Allready true');
                        }
                        break;
                }
                $teller++;
            }
            
        });

        return $validator;
    }

    public function sanitize()
    {
        $input = $this->all();

        foreach($input as $key => $data){
            if(is_array($data) && $key === 'conditions'){ 
            //[{dataId: a, comparisonOperator: "<", dataValue: 123}, {}]
                foreach($data as $i => $condition){
                    foreach($condition as $conditionKey => $conditionValue){
                        if($conditionKey === 'comparisonOperator'){
                            if(in_array($conditionValue, ["<", ">"])){
                                $condition[$conditionKey] = $conditionValue;
                                continue;
                            }
                        }
                        $condition[$conditionKey] = filter_var($conditionValue, FILTER_SANITIZE_STRING);
                    }
                    $data[$i] = $condition;
                }
                $input[$key] = $data;
            }elseif(is_array($data)){
                $input[$key] = filter_var_array($data, FILTER_SANITIZE_STRING);
            }else{
                $input[$key] = filter_var($data, FILTER_SANITIZE_STRING);
            }
        }

        //$input['financialScore'] = filter_var($input['name'], FILTER_SANITIZE_STRING);
        //$input['cfScore'] = filter_var($input['description'], FILTER_SANITIZE_STRING);

        $this->replace($input);     
    }
}