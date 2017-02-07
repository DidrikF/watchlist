<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalysisRequest extends FormRequest
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
    /*
    let data = {
                        financialScore: this.financialScore,
                        cfScore: this.cfScore,
                        growthScore: this.growthScore,
                        riskScore: this.riskScore,
                        textAnalysis: this.textAnalysis
                    };
    */
    public function rules()
    {
        $this->sanitize();

        return [
            'financialScore' => 'numeric|between:1,10',
            'cfScore' => 'numeric|between:1,10',
            'growthScore' => 'numeric|between:1,10',
            'riskScore' => 'numeric|between:1,10',
            'textAnalysis' => 'max:10000',
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
