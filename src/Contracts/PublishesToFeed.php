<?php

namespace JasperTey\ActivityFeed\Contracts;

interface PublishesToFeed
{
    /**
     * Serialized array representation of the object
     * on an activity feed.
     *
     * @return array
     */
    public function toFeed(): array;
}
