<?php

namespace App\Http\Resources;

use App\Models\PrimaryDatabase\User;
use Illuminate\Http\Request;

class UserResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var User $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'access_token' => $this->when($this->access_token && str_contains($request->getUri(), 'auth'), function () {
                return $this->access_token;
            }),
            ...$this->getRelatedResources(),
            ...$this->getTimestamps(),
        ];
    }
}
