<?php

namespace App\Listeners;

use App\Events\ActivityLogCreated;
use App\Models\Badge;
use App\Models\UserUnitTotal;
use App\User;
use Illuminate\Support\Facades\DB;

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
        $steps = $activityJson['steps'];
        $user = User::find($event->userID);

        UserUnitTotal::updateOrCreate([
            'user_id' => $event->userID,
            'unit_id' => 1
        ], [
            // 'grand_total' => DB::raw("grand_total + $steps"),
        ])->increment('grand_total', $steps);

        /*$user->unitTotal()->updateExistingPivot(1, [
            'grand_total' => DB::raw("grand_total + $steps")
        ]);*/

        $updatedGrandTotal = UserUnitTotal::where([
            'user_id' => $event->userID,
            'unit_id' => 1
        ])->select('grand_total')->first()->grand_total;

        Badge::select('id', 'target')
            ->where('target', '<=', $updatedGrandTotal)
            ->where('unit_id', 1)
            ->get('id')->map(function ($item) use ($user) {
                $user->badges()->syncWithoutDetaching($item->id);
            });
    }
}
