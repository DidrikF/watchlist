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
    protected $notification;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Notification $notification)
    {
        parent::__construct();
        $this->notification = $notification;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        
        $notifications = $this->notification->all();

        foreach($notifications as $notification){ //go through all notification entries

            $items = $notification->notificationCondition()->get(); //items related to one notiication

            if($this->checkConditions($items, $notification->ticker)){
                $user = (new User)->where('id', $notification->user_id)->get();
                Mail::to($user)->send(new \App\Mail\Notification);

                //delete Notification
            }
        }
    }

    private function checkConditions(Collection $conditions, $ticker)
    {
        foreach($conditions as $condition){
            
            $valueFromYahoo = $this->getYahooData($ticker, $condition->data_id); //$item->data_id ....

            switch($condition->comparison_operator)
            {
                case "=":
                    if($valueFromYahoo != $condition->data_value) {
                        return false;
                    }
                    break;
                case "<":
                    if($valueFromYahoo >= $condition->data_value) {
                        return false;
                    }
                    break;
                case ">":
                    if($valueFromYahoo <= $condition->data_value) {
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

        return $response->getBody();
    }

}
