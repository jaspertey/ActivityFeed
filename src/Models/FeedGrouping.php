<?php

namespace JasperTey\ActivityFeed\Models;

use Illuminate\Database\Eloquent\Model;

class FeedGrouping extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function activity()
    {
        return $this->belongsTo(FeedActivity::class, 'activity_id');
    }

    public static function remove($activityId, $context = null)
    {
        return static::query()
            ->where('activity_id', $activityId)
            ->whereContext($context)
            ->delete();
    }

    public static function assign($activityId, $hash, $context = null)
    {
        return static::query()
            ->updateOrCreate([
                'activity_id' => $activityId,
                'context' => $context,
            ], [
                'hash' => $hash
            ]);
    }
}
