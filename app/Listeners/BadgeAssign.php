<?php

namespace App\Listeners;

use App\Events\ActivityLogCreated;
use App\Models\ActivityLog;
use App\Models\Badge;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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
     * @param  ActivityLogCreated  $event
     * @return void
     */
    public function handle(ActivityLogCreated $event)
    {
        $activityJson = json_decode($event->activityLog->activity, TRUE);
        $steps = $activityJson['steps'];

        \App\Models\UserUnitTotal::updateOrCreate([
            'user_id' => $event->userID,
            'unit_id' => 1
        ], [
            'grand_total' => DB::raw("grand_total::float + $steps"),
        ])->limit(1); // optional - to ensure only one record is updated.

        // $badges = Badge::select('id', 'target')->get();

    }
}
