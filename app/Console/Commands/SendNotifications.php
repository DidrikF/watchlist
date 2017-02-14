<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Auth;

use App\Models\Notification;
use App\Models\NotificationCondition;
use App\Models\User;

use GuzzleHttp\Client;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check notification parameters and send an email if they are true';

    /**
     * The Notification model
     *
     * @var Notification
     */
    //protected $notification;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() //Notification $notification
    {
        parent::__construct();
        //$this->notification = $notification;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        
        $notifications = (new Notification)->where('triggered', false)->get();

        foreach($notifications as $notification){ //go through all notification entries
            if(!(new User)->where('id', $notification->user_id)->exists()){
                continue;
            }
            $conditions = $notification->notificationCondition()->get(); //items related to one notiication

            if($this->checkConditions($conditions, $notification->ticker)){
                $user = (new User)->where('id', $notification->user_id)->first();
                
                $notification->triggered = true;
                $notification->save();

                Mail::to($user)->send(new \App\Mail\NotificationMail($notification, $conditions, $user));
            }
        }
    }

    private function checkConditions(Collection $conditions, $ticker)
    {
        $arr;
        foreach($conditions as $condition){
            
            $valueFromYahoo = $this->getYahooData($ticker, $condition->data_id); //$item->data_id ....
            //if just one of the conditions are false, return false.
            switch($condition->comparison_operator)
            {
                case "<":
                    if($this->parseYahooData($valueFromYahoo) >= $this->parseYahooData($condition->data_value)) {
                        return false;
                    }
                    break;
                case ">":
                    if($this->parseYahooData($valueFromYahoo) <= $this->parseYahooData($condition->data_value)) {
                        return false;
                    }
                    break;
            }
        }
        return true;
    }

    private function getYahooData($ticker, $dataId)
    {
        $url = "http://finance.yahoo.com/d/quotes.csv?s={$ticker}&f={$dataId}";
        //Create guzzle client
        $client = new Client;
        //send request
        $response = $client->request('GET', $url);
        return $response->getBody()->getContents();
    }

    private function parseYahooData($data)
    {
        $arr = str_split(trim($data));
        if(strtoupper($arr[count($arr)-1]) === 'B'){
            array_pop($arr);
            $num = (float) implode('', $arr);
            return $num * 1000000000;
        }
        elseif(strtoupper($arr[count($arr)-1]) === 'M'){
            array_pop($arr);
            $num = (float) implode('', $arr);
            return $num * 1000000;
        }
        elseif(strtoupper($arr[count($arr)-1]) === 'K'){
            array_pop($arr);
            $num = (float) implode('', $arr);
            return $num * 1000;
        }
        return (float) implode('', $arr);
    }

}
