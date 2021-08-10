<?php

namespace App\Http\Resources\V1\Users;

use App\Http\Resources\V1\Catalogues\CatalogueResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'domain' => $this->domain,
            'icon' => $this->icon,
        ];
    }
}
