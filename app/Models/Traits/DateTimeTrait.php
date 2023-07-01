<?php

namespace App\Models\Traits;

trait DateTimeTrait
{
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        // return format date same as version laravel5.5
        return $date->format('Y-m-d H:i:s');
    }
}