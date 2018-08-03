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

    protected $yahooDataArray;
    private $keys = ['p', 'y', 'd', 't8', 'm4', 'g3', 's6', 'w', 'j1', 'v', 'e', 'b4', 'j4', 'p5', 'p6', 'r', 'r5', 's7'];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        
        $notifications = (new Notification)->where('triggered', false)->get();

        $valueFromYahoo = $this->getYahooData($notifications);

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
            
            $valueFromYahoo = $this->getLocalCompanyData($ticker, $condition->data_id);
            if(!$valueFromYahoo){
                continue; 
                //if no data or data is 'N/A', skip the condition (condition that is 'N/A' should not be allowed to be created)
            }
            //if just one of the conditions are false, return false.
            switch($condition->comparison_operator)
            {
                case "<":
                    if($this->parseYahooData($valueFromYahoo) >= $this->parseYahooData($condition->data_value)) {
                        //var_dump('FALSE');
                        return false;
                    }
                    break;
                case ">":
                    if($this->parseYahooData($valueFromYahoo) <= $this->parseYahooData($condition->data_value)) {
                        //var_dump('FALSE');
                        return false;
                    }
                    break;
            }
        }
        //var_dump('TRUE');
        return true;
    }

    private function getYahooData($notifications)
    {
        $uniqueTickers = [];
        foreach($notifications as $notification){
            if(!in_array($notification->ticker, $uniqueTickers)){
                $uniqueTickers[] = $notification->ticker;
            } 
        }
        $data = [];
        $client = new Client; 
        foreach($uniqueTickers as $t){
            /* MOCK THIS PART! */
            $values = $client->request('GET', "http://finance.yahoo.com/d/quotes.csv?s={$t}&f=pydt8m4g3s6wj1veb4j4p5p6rr5s7")->getBody()->getContents();
            $valuesArr = str_getcsv($values, ',', '"');
            if(count($this->keys) !== count($valuesArr)){
                $data[$t] = '';
                continue;
            }
            $keyValues = array_combine($this->keys, $valuesArr);
            //var_dump($valuesArr);
            $data[$t] = $keyValues;   
        }
        $this->yahooDataArray = $data;
    }

    private function getLocalCompanyData($ticker, $dataId){
        if(count($this->yahooDataArray) && $this->yahooDataArray[$ticker][$dataId] !== 'N/A'){
            return $this->yahooDataArray[$ticker][$dataId];
        }
        return false;
    }

    private function parseYahooData($data) //(what about 'N/A')
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

        /*
    private $keyTranslation = ["p": "Previous close", "y": "Dividend yield", "d": "Dividend per share", "t8": "1 year target price", "m4": "200 day moving avg", "g3": "Annualizd gain", "s6": "Revenue", "w": "52 week range", "j1": "Market capitalization", "v": "Volume", "e": "EPS", "b4": "Book value", "j4": "EBITDA", "p5": "Price/Sales", "p6": "Price/Book", "r": "P/E ratio", "r5": "PEG ratio", "s7": "Short ratio"]
    */

}
