<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class CustomNotificationBuilder extends Builder
{
    /**
     * Add a basic where clause to the query.
     *
     * @param  \Closure|string|array|\Illuminate\Database\Query\Expression  $column
     * @param  mixed  $operator
     * @param  mixed  $value
     * @param  string  $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if (is_string($column) && str_starts_with($column, 'data->')) {
            if (func_num_args() === 2) {
                $val = $operator;
                $op = '=';
            } else {
                $op = $operator;
                $val = $value;
            }

            if ($column === 'data->format' && $op === '=' && $val === 'filament') {
                return $this->where('data', 'like', '%"format":"filament"%', $boolean);
            }
        }

        return parent::where($column, $operator, $value, $boolean);
    }
}
