<?php

namespace JasperTey\ActivityFeed\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PublishableObjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $resource = $this->resource;

        if ($resource && method_exists($resource, 'toFeed')) {
            return $resource->toFeed($request);
        }

        return parent::toArray($request);
    }
}
