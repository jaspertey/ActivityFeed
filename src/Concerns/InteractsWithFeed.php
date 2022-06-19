<?php

namespace JasperTey\ActivityFeed\Concerns;

use JasperTey\ActivityFeed\Models\FeedActivity;

trait InteractsWithFeed
{
    public function deleteFromFeed()
    {
        return FeedActivity::query()
            ->involving($this)
            ->delete();
    }

    public function forceDeleteFromFeed()
    {
        return FeedActivity::query()
            ->withTrashed()
            ->involving($this)
            ->forceDelete();
    }
}
