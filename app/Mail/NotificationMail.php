<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\{Notification, User};

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $yahooKeyTranslation = ["p" => "Previous close", "y" => "Dividend yield", "d" => "Dividend per share", "d1" => "Last trade date", "t8" => "1 year target price", "m4" => "200 day moving avg", "g3" => "Annualizd gain", "s6" => "Revenue", "w" => "52 week range", "j1" => "Market capitalization", "n" => "Name", "x" => "Stock exchange", "j2" => "Shares outstanding", "v" => "Volume", "e" => "EPS", "b4" => "Book value", "j4" => "EBITDA", "p5" => "Price/Sales", "p6" => "Price/Book", "r" => "P/E ratio", "r5" => "PEG ratio", "s7" => "Short ratio"];

    protected $notification;
    protected $conditions;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Notification $notification, $conditions, User $user)
    {
        $this->notification = $notification;
        $this->conditions = $conditions;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification', [
                'notification' => $this->notification,
                'conditions' => $this->conditions,
                'yahooKeyTranslation' => $this->yahooKeyTranslation,
                'user' => $this->user
            ]);
    }
}
