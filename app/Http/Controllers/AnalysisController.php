<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Analysis;

class AnalysisController extends Controller
{
    public function show(Request $request, Analysis $analysis)  //the correct instance of the analysis model is injected through route model binding!
    {

    	$response = [
    		'financialScore' => $analysis->financial,
    		'cfScore' => $analysis->cash_flow,
    		'growthScore' => $analysis->growth_potential,
    		'riskScore' => $analysis->risk,
    		'textAnalysis' => $analysis->text_analysis,
    	];

    	return response()->json([
    		'data' => $response
    	], 200);
    }

    public function save() 
    {

    }

    public function delete()
    {

    }

    //function code

    /*

	this.financialScore = response.json().data.financialScore;
	this.cfScore = response.json().data.cfScore;
	this.growthScore = response.json().data.growthScore;
	this.riskScore = response.json().data.riskScore;
	this.textAnalysis = response.json().data.textAnalysis;
	this.ticker = response.json().data.ticker;

    */
}