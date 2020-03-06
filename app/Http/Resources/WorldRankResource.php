<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorldRankResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'headshot' => $this->headshot,
            'city' => $this->profile->city,
            'country' => $this->profile->country,
            'grand_total_distance' => $this->grand_total_distance,
        ];
    }
}
