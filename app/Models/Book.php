<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * Book Records relationship
     */
    public function records()
    {
        return $this->hasMany('App\Models\BookRecord');
    }

    /**
     * Author relationship
     */
    public function author()
    {
        return $this->belongsTo('App\Models\Writer', 'writer_id');
    }

    /**
     * Category relationship
     */
    public function category()
    {
        return $this->belongsTo('App\Models\BookCategory', 'book_category_id');
    }

}
