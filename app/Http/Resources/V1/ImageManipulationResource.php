<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class ImageManipulationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'original' => URL::to($this->path),
            'output_path' => URL::to($this->output_path),
            'data' => $this->data,
            'user_id' => $this->user_id,
            'album_id' => $this->album_id,
            'created_at' => $this->created_at,
        ];
    }
}
