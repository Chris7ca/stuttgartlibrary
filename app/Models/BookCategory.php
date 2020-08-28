<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookCategory extends Model
{
    
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * Books relationship
     * 
     */
    public function books()
    {
        return $this->hasMany('App\Models\Book');
    }

}
