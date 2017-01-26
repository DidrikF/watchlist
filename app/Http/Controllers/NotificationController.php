<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Notification;
use App\Models\NotificationCondition;

class NotificationController extends Controller
{

    public function read($ticker)
    {

    }

    public function readAll(User $user)
    {

    }

    public function create($ticker, Request $request)
    {
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

        return response()->json(null, 201);

    }

    public function delete($ticker)
    {

    }
}
