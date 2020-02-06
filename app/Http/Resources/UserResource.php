<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'user_code' => $this->user_code,
            'phone' => $this->phone,
            'height' => $this->height,
            'weight' => $this->weight,
            'phone_verified_at' => $this->phone_verified_at,
            'headshot' => $this->headshot,
            'role' => $this->roles[0]->display_name,
        ];
    }
}
