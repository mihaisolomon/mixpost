<?php

namespace Inovector\Mixpost\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inovector\Mixpost\Builders\Filters\PostAccounts;
use Inovector\Mixpost\Builders\Filters\PostKeyword;
use Inovector\Mixpost\Builders\Filters\PostTags;
use Inovector\Mixpost\Builders\Filters\PostStatus;
use Inovector\Mixpost\Contracts\Query;
use Inovector\Mixpost\Model\Post;

class PostQuery implements Query
{
    public static function apply(Request $request): Builder
    {
        $query = Post::with('accounts', 'versions', 'tags');

        if ($request->has('status') && $request->get('status') !== null) {
            $query = PostStatus::apply($query, $request->get('status'));
        }

        if ($request->has('keyword') && $request->get('keyword')) {
            $query = PostKeyword::apply($query, $request->get('keyword'));
        }

        if ($request->has('accounts' && !empty($request->get('accounts')))) {
            $query = PostAccounts::apply($query, $request->get('accounts', []));
        }

        if ($request->has('tags' && !empty($request->get('tags')))) {
            $query = PostTags::apply($query, $request->get('tags', []));
        }

        return $query;
    }
}
