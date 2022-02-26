<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;

class CreatedProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($createdProject)
    {
        return [
            'project' => $createdProject,
            'message' => 'プロジェクトが追加されました',
            'error' => '',
        ];
    }
}
