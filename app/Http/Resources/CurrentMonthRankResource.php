<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrentMonthRankResource extends JsonResource
{
    protected $currentMonthDistance = 0;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        foreach ($this->currentMonthActivityLog as $eachLog) {
            $activity = json_decode($eachLog->activity);
            $this->currentMonthDistance += $activity->distance;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'headshot' => $this->headshot,
            'city' => $this->profile->city,
            'country' => $this->profile->country,
            'grand_total_distance' => $this->grand_total_distance,
            'current_month_total_distance' => $this->currentMonthDistance,
        ];
    }
}
