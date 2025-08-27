<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $isDeleted = str_contains($this->description, 'deleted');

        return [
            'content' => ($this->user->name ?? 'Unknown User') . ' ' . $this->description,
            'timestamp' => date('M d, Y h:i A', strtotime($this->created_at)),
            'size' => 'large',
            'type' => 'primary',
            'color' => $isDeleted ? 'red' : '#0bbd87'
        ];
    }
}
