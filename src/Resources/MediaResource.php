<?php

namespace Inovector\Mixpost\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mime_type' => $this->mime_type,
            'url' => $this->getUrl(),
            'thumb_url' => $this->getThumbUrl()
        ];
    }
}
