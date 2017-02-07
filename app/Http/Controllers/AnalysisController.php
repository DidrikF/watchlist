<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Requests\Request;

use App\Http\Requests\AnalysisRequest;

use Illuminate\Support\Facades\Auth;

use App\Models\Analysis;

use App\Models\User;

class AnalysisController extends Controller
{
    public function read($ticker, Request $request)
    {
        $this->authorize('read', $analysis);

        $user = Auth::user();

        if(!Analysis::where('user_id', $user->id)->where('ticker', $ticker)->exists()){
            return response()->json(null, 404);
        }
        $analysis = Analysis::where('user_id', $user->id)->where('ticker', $ticker)->first();
        
    	$response = [
    		'financialScore' => $analysis->financial,
    		'cfScore' => $analysis->cash_flow,
    		'growthScore' => $analysis->growth_potential,
    		'riskScore' => $analysis->risk,
    		'textAnalysis' => $analysis->text_analysis,
    	];

    	return response()->json($response, 200);
    }

    public function create($ticker, AnalysisRequest $request) //FORM REQUEST
    {
        $ticker = filter_var($ticker, FILTER_SANITIZE_STRING);

        $user = Auth::user();
        if(Analysis::where('user_id', $user->id)->where('ticker', $ticker)->exists()){
            return response()->json(null, 302); //Found
        }

        $analysis = new Analysis;
        
        $analysis->user_id = $user->id;
        $analysis->ticker = $ticker;
        $analysis->financial = $request->financialScore;
        $analysis->cash_flow = $request->cfScore;
        $analysis->growth_potential = $request->growthScore;
        $analysis->risk = $request->riskScore;
        $analysis->text_analysis = $request->textAnalysis;
        $analysis->save(); //if save successfull then return positive response
        return response()->json(null, 200);
    }

    public function update($ticker, AnalysisRequest $request) //FORM REQUEST
    {
        $ticker = filter_var($ticker, FILTER_SANITIZE_STRING);
        
        $analysis = Analysis::where('user_id', $user->id)->where('ticker', $ticker)->first();
        
        $this->authorize('update', $analysis);

        $user = Auth::user();
        if(!Analysis::where('user_id', $user->id)->where('ticker', $ticker)->exists()){
            return response()->json(null, 404);
        }

        $analysis = Analysis::where('user_id', $user->id)->where('ticker', $ticker)->first();
        $analysis->financial = $request->financialScore;
        $analysis->cash_flow = $request->cfScore;
        $analysis->growth_potential = $request->growthScore;
        $analysis->risk = $request->riskScore;
        $analysis->text_analysis = $request->textAnalysis;
        $analysis->save(); //if save successfull then return positive response
        return response()->json(null, 200);
    }

    public function delete($ticker)
    {
        $analysis = Analysis::where('user_id', Auth::user()->id)->where('ticker', $ticker)->first();
        $this->authorize('delete', $analysis);

        if($analysis->delete()) {
            return response()->json(null, 200);
        }
        return response()->json(null, 404);

    }

}