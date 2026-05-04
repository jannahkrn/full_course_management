<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseMaterialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'session_id'   => $this->session_id,
            'title'        => $this->title,
            'description'  => $this->description,
            'type'         => $this->type,
            'file_path'    => $this->file_path,
            'file_url'     => $this->file_url,
            'file_name'    => $this->file_name,
            'file_size_kb' => $this->file_size_kb,
            'order'        => $this->order,
            'is_active'    => $this->is_active,
        ];
    }
}