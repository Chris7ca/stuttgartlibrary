<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookRecord extends Model
{
    
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * Borrowed relatioship
     */
    public function borrowedBook()
    {
        return $this->belongsToMany('App\Models\User', 'borrowed_books');
    }

}
