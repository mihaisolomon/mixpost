<?php

namespace Inovector\Mixpost\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Inovector\Mixpost\Models\Media;
use Inovector\Mixpost\Util;

class PostVersionResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request)
    {
        return [
            'post_id' => $this->post_id,
            'account_id' => $this->account_id,
            'is_original' => $this->is_original,
            'content' => $this->content()
        ];
    }

    protected function isIndexPage(): bool
    {
        return request()->route()->getName() === 'mixpost.posts.index';
    }

    protected function isCalendarPage(): bool
    {
        return request()->route()->getName() === 'mixpost.calendar';
    }

    protected function content(): Collection
    {
        $mediaCollection = $this->mediaCollection();

        return collect($this->content)->map(function ($item) use ($mediaCollection) {
            $data = [
                'body' => (string)$item['body'],
                'media' => collect($item['media'])->map(function ($mediaId) use ($mediaCollection) {
                    $media = $mediaCollection->where('id', $mediaId)->first();

                    if (!$media) {
                        return null;
                    }

                    return new MediaResource($media);
                })->filter()->values()
            ];

            if ($this->isIndexPage()) {
                $data['excerpt'] = Str::limit(Util::removeHtmlTags($item['body']), 150);
            }

            if($this->isCalendarPage()) {
                $data['excerpt'] = Str::limit(Util::removeHtmlTags($item['body']), 50);
            }

            return $data;
        });
    }

    protected function mediaCollection()
    {
        $mediaIds = [];

        foreach ($this->content as $item) {
            $mediaIds = array_merge($mediaIds, $item['media']);
        }

        return Media::whereIn('id', $mediaIds)->get();
    }
}
