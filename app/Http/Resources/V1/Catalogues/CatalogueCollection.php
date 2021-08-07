<?php

namespace App\Http\Resources\V1\Catalogues;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CatalogueCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
