<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class User extends JsonResource
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

            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,

            'activated' => $this->activated,
            'dark_mode' => $this->dark_mode,
            'is_admin' => $this->is_admin,
        ];
    }
}
