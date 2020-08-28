<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class Library
{

    /**
     * Search in library
     * 
     * @return collection
     */
    public function search(String $queryString)
    {
        $writers = $this->searchInWirters($queryString);
        $books   = $this->searchInBooks($queryString);

        return response()->json([
            'books' => $books,
            'writers' => $writers
        ]);
    }

    private function searchInBooks(String $book)
    {
        return DB::table('books')
        ->selectRaw("slug, title")
        ->where('title', 'like', "%$book%")
        ->whereNull('deleted_at')
        ->limit(4)
        ->get();
    }

    private function searchInWirters(String $writer)
    {
        return DB::table('writers')
        ->selectRaw("slug, CONCAT(name,' ',lastname) as name")
        ->where(DB::raw("CONCAT(name,' ',lastname)"), 'like', "%$writer%")
        ->whereNull('deleted_at')
        ->limit(4)
        ->get();
    }

    /**
     * Get records belongs to user
     * 
     * @return collection
     */
    public function usersBooks(int $user)
    {
        return DB::table('borrowed_books as p')
        ->selectRaw("b.title as book, c.title as category")
        ->addSelect(DB::raw("CONCAT(w.name,' ',w.lastname) as author"))
        ->addSelect(DB::raw("r.isbn, p.delivery_date, p.deadline, p.return_date"))
        ->join('book_records as r', 'p.book_record_id', '=', 'r.id')
        ->join('books as b', 'r.book_id', '=', 'b.id')
        ->join('book_categories as c', 'b.book_category_id', '=', 'c.id')
        ->join('writers as w', 'b.writer_id', '=', 'w.id')
        ->join('users as u', 'p.user_id', '=', 'u.id')
        ->where('u.id', $user)
        ->orderBy(DB::raw("p.return_date is null"), 'desc')
        ->orderBy('p.delivery_date', 'desc')
        ->paginate(10);
    }

}