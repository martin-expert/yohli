<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Slug
{

   /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = (string) Str::slug($model->title ."-". time());
        });

        static::updating(function ($model) {
            $model->slug = (string) Str::slug($model->title ."-". time());
        });
    }
}
   