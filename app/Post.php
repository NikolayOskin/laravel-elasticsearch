<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Search\Searchable;

class Post extends Model
{
    use Searchable;

    protected $table = 'posts';

    protected $fillable = [
        'title', 
        'body',
    ];
}
