<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;

class PendingRequestResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     */
    public function toArray($request)
    {
        return [
            'id' => $this->sender->id,
            'name' => $this->sender->name,
            'email' => $this->sender->email,
            'phone' => $this->sender->phone,
            'headshot' => $this->sender->headshot,
            'address' => $this->sender->profile->address
        ];
    }

}
