<?php

namespace JasperTey\ActivityFeed\Contracts;

interface PublishesToFeed
{
    /**
     * Serialized array representation of the object
     * on an activity feed.
     */
    public function toFeed(): array;

    public function feedLabel(): string;
}
