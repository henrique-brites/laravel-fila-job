<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\PinguimDoLaravelEhTop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class SendNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $count = 1;

        User::query()->chunk(3,  function ($users) use ($count) {

            $listOfAlljobs = [];
            foreach($users as $user) {

                $job = new SendNotificationJob($user);

                $listOfAlljobs[] =  $job;
            }

            Bus::batch($listOfAlljobs)->name("Batch Sending Notifications " . $count)->allowFailures()->dispatch();
            $count++;

        });

        // $listOfAlljobs = [];
        // foreach(User::all() as $user) {
        //     $listOfAlljobs[] =  new SendNotificationJob($user);
        // }

        //Bus::batch($listOfAlljobs)->name("Sending Notifications")->allowFailures()->dispatch();

        //User::all()->each(fn (User $user) => $user->notify(new PinguimDoLaravelEhTop));
        //User::all()->each(fn (User $user) => SendNotificationJob::dispatch($user)->onQueue('redis-horizon-queue-sirius-long'));
    }
}
