<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
});


Route::get('cache-lock', function () {
    $lock = Cache::lock('bookings', 10);
    /* at least lock the lock for 10 second but even if the reqest take longer than 10 seconds give it time to.. if the request completes before the 10 seconds and i dont manually release the lock then it takes taht minimum time*/
    if ($lock->get()) {
        // get the lock and sleep 20 seconds
        // next time comment the sleep and make request to same route .. the request will wait for the code to finish
        // sleep(20);
        echo Str::random(5);
        // $lock->release(); // detete the entry of cache lock
    } else {
        // this only works when the other request finished but didn't leave the lock before ending
        //mean the lock is acquired but jis n bnaya tha woh  request is not being processed
        echo 'Another request is processing';
    }
});


Route::get('cache-lock1', function () {
    // same functionality as above
    Cache::lock('lock1', 20)->get(function () {
        echo Str::random(5);
    });
});


Route::get('cache-block', function () {
    // same functionality as above
    // get the lock and if you dont get the lock and no other request is using that .. wait for 3 seconds then throw exception
    Cache::lock('bookings', 10)->block(3, function () {
        sleep(5);
        // baqi it is same like above get lock just handles the else case wiht waiting for the lock may be woh khud e lock expire ho jai jo koi use nhe kr rha
        echo Str::random(5);
    });
});



Route::get('/permist-lock1', function (Request $request) {
    DB::beginTransaction();
    try {
        // Lock the user record with `lockForUpdate`
        // $user = User::where('id', 1)->sharedLock()->first();
        $user = User::where('id', 1)->sharedLock()->first();
        sleep(50);

        $user?->update(
            ['name' => 'sifro']
        );
        // shared lock is for read purpose.. if you are reading do this .. no other will be able to update it because tb tk koi exclusive lock nhe le sakta jab tk koi shared lock le k betha ho
        // exclusive is for write purpose.. it says i am writing .. so wait
        // Simulate processing time
        // sleep(10);

        // Commit the transaction to release the lock
        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
    }

    return response()->json($user);
});
Route::get('/permist-lock2', function (Request $request) {
    DB::beginTransaction();
    try {
        // Lock the user record with `lockForUpdate`
        // $user = User::where('id', 1)->sharedLock()->first();
        $user = User::where('id', 1)->sharedLock()->first();
        // sleep(10);

        $user?->update(
            ['name' => 'numo']
        );


        // Simulate processing time
        sleep(10);

        // Commit the transaction to release the lock
        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
    }
    return response()->json(['updated' => true]);
});



// Route::get('/cache-lock', function () {
//     $lock = Cache::lock('foo', 10);

//     try {
//         $lock->block(5);
//         sleep("10");
//         dump("asad");
//         // Lock acquired after waiting a maximum of 5 seconds...
//     } catch (LockTimeoutException $e) {
//         // Unable to acquire lock...
//         dump("error");
//     } finally {
//         $lock?->release();
//     }
// });
