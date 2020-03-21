<?php


namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PendingRequestCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function ($user) {
            return (new SenderResource($user));
        });

        return parent::toArray($request);
    }

}
