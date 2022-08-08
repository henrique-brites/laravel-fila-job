<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\PinguimDoLaravelEhTop;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 0;

    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    // public function middleware()
    // {
    //     return [
    //         new RateLimited('notifications')
    //     ];
    // }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->notify(new PinguimDoLaravelEhTop);
        //$this->user->notify((new PinguimDoLaravelEhTop)->onQueue('redis-horizon-queue-sirius-long'));
    }
}
