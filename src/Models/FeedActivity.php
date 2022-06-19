<?php

namespace JasperTey\ActivityFeed\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class FeedActivity extends Model
{
    use HasTimestamps, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $appends = [
        'summary'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->actor) {
                $model->actor()->associate(Auth::user());
            }
        });

        static::saved(function ($model) {
            $model->setGroupings();
        });

        static::saving(function ($model) {
            if (!$model->published_at) {
                $model->published_at = now();
            }
        });
    }

    public static function make(array $attributes = [])
    {
        return new static($attributes);
    }

    public function grouping()
    {
        return $this->hasOne(FeedGrouping::class, 'activity_id')
            ->whereContext(null);
    }

    public function actor()
    {
        return $this->morphTo();
    }

    public function object()
    {
        return $this->morphTo();
    }

    public function target()
    {
        return $this->morphTo();
    }

    public function scopePublished(Builder $query)
    {
        return $query->whereNotNull('published_at');
    }

    public function setGroupings()
    {
        FeedGrouping::assign($this->id, $this->family_hash);
    }

    public function getFamilyHashAttribute()
    {
        $hash = "{$this->actor_id}:{$this->type}:{$this->object_type}";

        if ($this->target_type) {
            $hash .= ":{$this->target_type}";
        }

        if ($this->target_id) {
            $hash .= ":{$this->target_id}";
        }

        if ($date = optional($this->published_at)->format('Y-m-d')) {
            $hash .= ":$date";
        }

        return $hash;
    }

    public function scopeContainsObject(Builder $query, $type, $id)
    {
        return $query->where(function ($query) use ($type, $id) {
            $query->orWhere(function ($query) use ($type, $id) {
                return $query->where('actor_type', $type)
                    ->where('actor_id', $id);
            });

            $query->orWhere(function ($query) use ($type, $id) {
                return $query->where('object_type', $type)
                    ->where('object_id', $id);
            });

            $query->orWhere(function ($query) use ($type, $id) {
                return $query->where('target_type', $type)
                    ->where('target_id', $id);
            });
        });
    }

    public function scopeActor(Builder $query, Model $model)
    {
        return $query->where('actor_type', $model->getMorphClass())
            ->where('actor_id', $model->getKey());
    }

    public function scopeObject(Builder $query, Model $model)
    {
        return $query->where('object_type', $model->getMorphClass())
            ->where('object_id', $model->getKey());
    }

    public function scopeTarget(Builder $query, Model $model)
    {
        return $query->where('target_type', $model->getMorphClass())
            ->where('target_id', $model->getKey());
    }

    public function scopeInvolving(Builder $query, $model)
    {
        return $query->where(function ($query) use ($model) {
            $query->orWhere->actor($model)
                ->orWhere->object($model)
                ->orWhere->target($model);
        });
    }

    public static function deleteAll($o = [])
    {
        $defaults = [
            'object_type' => '',
            'object_id' => '',
            'type' => null,
        ];
        $o += $defaults;

        $obj = data_get($o, 'object_type');
        $oid = data_get($o, 'object_id');

        $qry = static::query()
            ->whereContains($obj, $oid);

        if ($type = data_get($o, 'type')) {
            $qry->whereType($type);
        }

        return $qry->delete();
    }

    public function getSummaryAttribute()
    {
        return 'Actor did something with object in target';
    }
}
