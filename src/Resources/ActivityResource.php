<?php

namespace JasperTey\ActivityFeed\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'actor' => $this->whenLoaded(
                'actor',
                PublishableObjectResource::make($this->actor)->toArray($request)
            ),

            'object' => $this->whenLoaded(
                'object',
                PublishableObjectResource::make($this->object)->toArray($request)
            ),

            'target' => $this->whenLoaded(
                'target',
                PublishableObjectResource::make($this->target)->toArray($request)
            ),
        ] + parent::toArray($request);
    }
}
