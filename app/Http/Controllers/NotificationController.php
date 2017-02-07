<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\{Notification, NotificationCondition};


//use Illuminate\Http\Request;
use App\Http\Requests\Request;
use App\Http\Requests\NotificationRequest;

use GuzzleHttp\Client;

class NotificationController extends Controller
{
    public $yahooKeyTranslation = ["p" => "Previous close", "y" => "Dividend yield", "d" => "Dividend per share", "d1" => "Last trade date", "t8" => "1 year target price", "m4" => "200 day moving avg", "g3" => "Annualizd gain", "s6" => "Revenue", "w" => "52 week range", "j1" => "Market capitalization", "n" => "Name", "x" => "Stock exchange", "j2" => "Shares outstanding", "v" => "Volume", "e" => "EPS", "b4" => "Book value", "j4" => "EBITDA", "p5" => "Price/Sales", "p6" => "Price/Book", "r" => "P/E ratio", "r5" => "PEG ratio", "s7" => "Short ratio"];

    /*
    public function read($ticker)
    {
        $this->authorize('read', $notification); //by authorizing notification, we can assume $conditions are also authorized, as they are fetched via the notification model.
    }

    public function readAll(User $user)
    {
        $this->authorize('readAll', $notification);
    }
    */

    //Being authenticated is only requirement
    public function create($ticker, NotificationRequest $request)
    {
        $ticker = filter_var($ticker, FILTER_SANITIZE_STRING);

        $notification = new Notification;
        
        $notification->user_id = Auth::user()->id;
        $notification->ticker = $ticker;
        $notification->name = $request->name;
        $notification->description = $request->description;

        $notification->save();


        foreach($request->conditions as $condition) {
            $nc = new NotificationCondition;
            $nc->notification_id = $notification->id;
            $nc->data_id = $condition["dataId"];
            $nc->comparison_operator = $condition["comparisonOperator"];
            $nc->data_value = $condition["dataValue"];
            $nc->save();
        }

        $conditions = $notification->notificationCondition()->get();
        foreach($conditions as $condition){
            $conditionsArray[] = [
                'dataId' => $condition->data_id,
                'dataName' => $this->yahooKeyTranslation[$condition->data_id],
                'comparisonOperator' => $condition->comparison_operator,
                'dataValue' => $condition->data_value
            ];
        }

        $noti = (new Notification)->where('id', $notification->id)->first();
        return response()->json([
                'notification' => [
                        'id' => $noti->id,
                        'name' => $noti->name,
                        'description' => $noti->description,
                        'triggered' => $noti->triggered,
                        'conditions' => $conditionsArray,
                    ],
            ], 201);

    }

    public function update(Notification $notification, $ticker, NotificationRequest $request)
    {
        $this->authorize('update', $notification);

        $ticker = filter_var($ticker, FILTER_SANITIZE_STRING);
        
        $notification->name = $request->name;
        $notification->description = $request->description;
        $notification->save();

        //Delete and then recreate notification conditions
        NotificationCondition::where('notification_id', $notification->id)->delete();

        foreach($request->conditions as $condition) {
            $nc = new NotificationCondition;
            $nc->notification_id = $notification->id;
            $nc->data_id = $condition["dataId"];
            $nc->comparison_operator = $condition["comparisonOperator"];
            $nc->data_value = $condition["dataValue"];
            $nc->save();
        }

        $conditions = $notification->notificationCondition()->get();
        foreach($conditions as $condition){
            $conditionsArray[] = [
                'dataId' => $condition->data_id,
                'dataName' => $this->yahooKeyTranslation[$condition->data_id],
                'comparisonOperator' => $condition->comparison_operator,
                'dataValue' => $condition->data_value
            ];
        }

        return response()->json([
                'notification' => [
                        'id' => $notification->id,
                        'name' => $notification->name,
                        'description' => $notification->description,
                        'triggered' => $notification->triggered,
                        'conditions' => $conditionsArray, //need to do as in company controller
                    ],
            ], 200);
    }

    public function delete(Notification $notification)
    {
        $this->authorize('delete', $notification);
        
        if($notification->delete()) {
            return response()->json(null, 200);
        }
        return response()->json(null, 404);
    }
}
