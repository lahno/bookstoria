<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'weigth'
    ];

    public function books()
    {
        return $this->HasMany('App\Book');
    }
}
