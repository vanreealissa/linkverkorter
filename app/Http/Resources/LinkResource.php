<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Link */
class LinkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'url' => $this->url,
            'short_url' => $this->shortUrl(),
            'clicks' => $this->clicks_count,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
