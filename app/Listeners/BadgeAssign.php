<?php

namespace App\Listeners;

use App\Events\ActivityLogCreated;
use App\Models\Badge;
use App\Models\BadgeUnit;
use App\Models\UserUnitTotal;
use App\User;
use Illuminate\Support\Facades\Log;

class BadgeAssign
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ActivityLogCreated $event
     */
    public function handle(ActivityLogCreated $event)
    {
        $activityJson = json_decode($event->activityLog->activity, true);

        $units = BadgeUnit::select('id', 'short_name')->get()->map(function ($unit) use ($activityJson, $event) {
            if (array_key_exists($unit->short_name, $activityJson)) {

                UserUnitTotal::updateOrCreate([
                    'user_id' => $event->userID,
                    'unit_id' => $unit->id
                ], [
                    // 'grand_total' => DB::raw("grand_total + $steps"),
                ])->increment('grand_total', $activityJson[$unit->short_name]);


                $updatedGrandTotal = UserUnitTotal::where([
                    'user_id' => $event->userID,
                    'unit_id' => $unit->id
                ])->select('grand_total')->first()->grand_total;


                $user = User::find($event->userID);
                Badge::select('id', 'target')
                    ->where('target', '<=', $updatedGrandTotal)
                    ->where('unit_id', $unit->id)
                    ->get('id')->map(function ($badge) use ($user) {
                        $user->badges()->syncWithoutDetaching($badge->id);
                    });

            } else {
                Log::info('has not ' . $unit->short_name);
            }
        });


        /*$user->unitTotal()->updateExistingPivot(1, [
            'grand_total' => DB::raw("grand_total + $steps")
        ]);*/


    }
}
