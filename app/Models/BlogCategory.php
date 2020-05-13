<?php

namespace App\Models;

use App\Traits\Slug;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use Slug;

    /**
    * @var  string
    */
    protected $table = 'blog_categories';

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->belongsToMany('App\Models\BlogPost');
    }

}