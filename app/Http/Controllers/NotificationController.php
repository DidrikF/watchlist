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
        $noti = (new Notification)->where('id', $notification->id)->first();
        return response()->json([
                'notification' => [
                        'id' => $noti->id,
                        'name' => $noti->name,
                        'description' => $noti->description,
                        'triggered' => $noti->triggered,
                    ],
            ], 201);

    }

    public function delete(Notification $notification, Request $request)
    {
        $this->authorize('delete', $notification);
        if($notification->delete()) {
            return response()->json(null, 200);
        }
        return response()->json(null, 404);
    }
}
