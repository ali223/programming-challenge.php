<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TaskResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'tasks',
            'id' => (string) $this->id,
            'attributes' => [
                'title' => $this->title,
                'completed' => (bool) $this->completed
            ]
        ];
    }
}