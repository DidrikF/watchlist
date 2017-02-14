<?php

namespace App\Jobs;

use App\Models\User;

use Illuminate\Support\Facades\Mail;

use App\Mail\UserRegistered;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserRegistered implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $admins = (new User)->where('admin', true)->get();

        Mail::to($admins)->send(new UserRegistered($this->user));

    }
}
