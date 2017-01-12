<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Analysis;

use App\Models\User;

class AnalysisController extends Controller
{
    //I'm pretty sure that the User model is not nessecary to insert.
    public function read($ticker, Request $request)  //the correct instance of the analysis model is injected through route model binding!
    {
        $user = Auth::user();
        //use the logged in user in stead, not the ID gotten via the route.

        //this could perhaps be swaped out with Model injection (need to inject model belonging to user with given ticker)
        if(!Analysis::where('user_id', $user->id)->where('ticker', $ticker)->exists()){
            return response()->json(null, 404);
        }
        $analysis = Analysis::where('user_id', $user->id)->where('ticker', $ticker)->first();
        
        $this->authorize('read', $analysis);
    	$response = [
    		'financialScore' => $analysis->financial,
    		'cfScore' => $analysis->cash_flow,
    		'growthScore' => $analysis->growth_potential,
    		'riskScore' => $analysis->risk,
    		'textAnalysis' => $analysis->text_analysis,
    	];

    	return response()->json($response, 200);
    }

    public function create($ticker, Request $request) //FORM REQUEST
    {
        $user = Auth::user();
        if(Analysis::where('user_id', $user->id)->where('ticker', $ticker)->exists()){
            return response()->json(null, 302); //Found
        }
        //Need to know about passing data to auth policies
        //$this->authorize('create', $analysis); //making sure there is no existign analysis
        if(Auth::user()->id != $user->id){     //I'd like to put this into AnalysisPolicy
            return response()->json(null, 401);
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

    public function update($ticker, Request $request) //FORM REQUEST
    {
        $user = Auth::user();
        if(!Analysis::where('user_id', $user->id)->where('ticker', $ticker)->exists()){
            return response()->json(null, 404);
        }
        $analysis = Analysis::where('user_id', $user->id)->where('ticker', $ticker)->first();
        $this->authorize('update', $analysis);

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
        $user = Auth::user();
        $analysis = Analysis::where('user_id', $user->id)->where('ticker', $ticker)->first();
        //$this->authorize('vote', $video);
        $analysis->delete();
        return response()->json(null, 200);

    }

}