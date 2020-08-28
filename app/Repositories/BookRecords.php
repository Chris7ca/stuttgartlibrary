<?php

namespace App\Repositories;

use App\Models\BookRecord;
use Illuminate\Foundation\Http\FormRequest;

class BookRecords
{

    /**
     * All records
     * 
     * @return collection
     */
    public function all(int $book)
    {
        return BookRecord::select('id','book_id','isbn','price')
        ->whereBookId($book)
        ->get();
    }

    /**
     * Update or create record
     * 
     * @return BookRecord
     */
    public function updateOrCreate(int $book, int $id = null, FormRequest $record)
    {
        return BookRecord::updateOrCreate(
            [ 'id' => $id ],
            [
                'book_id' => $book,
                'isbn'    => $record->isbn,
                'price'   => $record->price
            ]
        )->makeHidden('deleted_at');
    }

    /**
     * Delete record
     * 
     * @return status
     */
    public function delete(int $id)
    {
        return BookRecord::destroy($id);
    }

}