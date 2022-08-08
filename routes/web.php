<?php

use App\Jobs\MakeOrder;
use App\Jobs\RunPayment;
use App\Jobs\SendNotificationsJob;
use App\Jobs\ValidateCard;
use App\Models\User;
use App\Notifications\PinguimDoLaravelEhTop;
use Illuminate\Bus\BatchRepository;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (BatchRepository $batchRepository) {
    return view('welcome', [
        'bactches' => $batchRepository->get(),
    ]);
});

Route::get('send-notification-to-all', function () {
    SendNotificationsJob::dispatch()
    //->onConnection('redis-horizon-long-running')
    ->onQueue('high');

    //ProcessPodcast::dispatch()->onQueue('emails');
    // ProcessPodcast::dispatch($podcast)
    //           ->onConnection('sqs')
    //           ->onQueue('processing');

    return 'Ooii';

});

Route::get('run-batch', function () {

    Bus::batch([
        new MakeOrder,
        new ValidateCard,
        new RunPayment
    ])->name('Run Batch Exemle ', rand(1, 10))
    ->dispatch();

    return ('Run Batch Exemle');

});
