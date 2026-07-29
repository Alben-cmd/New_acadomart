<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;

class DatabaseNotification extends BaseDatabaseNotification
{
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \App\Models\CustomNotificationBuilder<static>
     */
    public function newEloquentBuilder($query)
    {
        return new CustomNotificationBuilder($query);
    }
}
