<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class BorrowedBooks
{

    /**
     * Paginated list
     * 
     * @return collection
     */
    public function paginatedList(String $search = null, String $exclude = null, String $sortBy = null, String $sortType = null)
    {
        return DB::table('borrowed_books as bb')
        ->selectRaw("bb.id, CONCAT(u.name,' ',u.lastname) AS user, b.title AS book, r.isbn")
        ->addSelect(DB::raw("bb.delivery_date, bb.deadline, bb.return_date"))
        ->leftJoin('book_records as r', 'bb.book_record_id', '=', 'r.id')
        ->leftJoin('books as b', 'r.book_id', '=', 'b.id')
        ->leftJoin('users as u', 'bb.user_id', '=', 'u.id')
        ->when($exclude == '-return_date', function($query) {
            $query->whereRaw("(bb.deadline < CURRENT_TIMESTAMP() AND bb.return_date IS NULL)");
        })
        ->when($exclude == 'return_date', function($query) {
            $query->whereRaw("(bb.deadline > CURRENT_TIMESTAMP() AND bb.return_date IS NULL)");
        })
        ->when($search != null, function($query) use ($search) {
            $query->whereRaw("(
                CONCAT(u.name,' ',u.lastname) LIKE '%$search%' OR
                b.title             LIKE '%$search%' OR
                r.isbn              LIKE '%$search%' OR
                bb.delivery_date    LIKE '%$search%' OR
                bb.deadline         LIKE '%$search%' OR
                bb.return_date      LIKE '%$search%'
            )");
        })
        ->when($sortBy != null && $sortType != null, function($query) use ($sortBy, $sortType) {
            $query->orderBy($sortBy, $sortType);
        })
        ->when($sortBy == null || $sortType == null, function($query) {
            $query->orderBy('bb.deadline', 'desc');
        })
        ->paginate(10);
    }

    /**
     * Unavailable records
     * 
     * @return collection
     */
    public function unavailableRecords(Array $records = [])
    {
        return DB::table('borrowed_books')
        ->select('book_record_id as record_id')
        ->whereIn('book_record_id', $records)
        ->whereNull('return_date')
        ->get();
    }

    /**
     * Store borrow
     * 
     * @return status
     */
    public function store(FormRequest $data)
    {
        return DB::table('borrowed_books')
        ->insert([
            'book_record_id' => $data->book_record_id,
            'user_id' => $data->user_id,
            'deadline' => $data->deadline
        ]);
    }

    /**
     * Store return book
     * 
     * @return status
     */
    public function returnBook(int $record)
    {
        return DB::table('borrowed_books')
        ->where('id', $record)
        ->update([
            'return_date' => now() 
        ]);
    }

}