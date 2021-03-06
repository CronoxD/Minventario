<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'amount',
        'price',
        'user_id',
    ];

    protected $primaryKey = 'article_id';
}
