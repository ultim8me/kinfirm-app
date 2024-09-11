<?php

namespace App\Http\Resources;


trait WithTimestamps
{
    public function getTimestamps(): array
    {
        return [
            'created_at' => $this->whenNotNull($this->created_at),
            'updated_at' => $this->whenNotNull($this->updated_at),
            'deleted_at' => $this->whenNotNull($this->deleted_at),
        ];
    }
}
