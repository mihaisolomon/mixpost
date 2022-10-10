<?php

namespace Inovector\Mixpost\Http\Requests;

use Illuminate\Support\Facades\DB;
use Inovector\Mixpost\Enums\PostStatus;
use Inovector\Mixpost\Model\Post;

class StorePost extends PostFormRequest
{
    public function handle()
    {
        return DB::transaction(function () {
            $record = Post::create([
                'status' => PostStatus::DRAFT,
                'scheduled_at' => $this->input('date') && $this->input('time') ? "{$this->input('date')} {$this->input('time')}" : null
            ]);

            $record->accounts()->attach($this->input('accounts', []));
            $record->tags()->attach($this->input('tags'));
            $record->versions()->createMany($this->input('versions'));

            return $record;
        });
    }
}
