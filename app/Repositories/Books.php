<?php

namespace App\Repositories;

use Faker\Factory;
use App\Models\Book;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class Books
{
    /**
     * Recently published books
     * 
     * @return collection
     */
    public function recentlyPublished()
    {
        return DB::table('books as b')
        ->selectRaw("b.slug, b.title, b.image, b.published_date, CONCAT(w.name,' ',w.lastname) AS author, w.slug as author_slug")
        ->addSelect(DB::raw("(SELECT COUNT(*) FROM book_records tr WHERE tr.book_id = b.id and tr.deleted_at IS NULL) AS existences"))
        ->addSelect(DB::raw("(SELECT COUNT(*) FROM borrowed_books tp LEFT JOIN book_records tr ON tp.book_record_id = tr.id 
        WHERE tr.book_id = b.id AND tp.return_date IS NULL) AS borrowed_books"))
        ->leftJoin('book_categories as c', 'b.book_category_id', '=', 'c.id')
        ->leftJoin('writers as w', 'b.writer_id', '=', 'w.id')
        ->whereYear('b.published_date', now()->year)
        ->whereNull('b.deleted_at')
        ->orderBy('b.published_date')
        ->get();
    }

    /**
     * Random books
     * 
     * @return collection
     */
    public function randomBooks()
    {
        return DB::table('books as b')
        ->selectRaw("b.slug, b.title, b.image, CONCAT(w.name,' ',w.lastname) AS author, w.slug as author_slug")
        ->addSelect(DB::raw("(SELECT COUNT(*) FROM book_records tr WHERE tr.book_id = b.id and tr.deleted_at IS NULL) AS existences"))
        ->addSelect(DB::raw("(SELECT COUNT(*) FROM borrowed_books tp LEFT JOIN book_records tr ON tp.book_record_id = tr.id 
        WHERE tr.book_id = b.id AND tp.return_date IS NULL) AS borrowed_books"))
        ->leftJoin('book_categories as c', 'b.book_category_id', '=', 'c.id')
        ->leftJoin('writers as w', 'b.writer_id', '=', 'w.id')
        ->whereNull('b.deleted_at')
        ->inRandomOrder()
        ->limit(20)
        ->get();
    }

    /**
     * Search by title
     * 
     * @return collection
     */
    public function searchByTitle(String $title = '')
    {
        return Book::select('id','title')
        ->where('title', 'like', "%$title%")
        ->limit(10)
        ->get();
    }

    /**
     * Paginated list
     * 
     * @return collection
     */
    public function paginatedList(String $category = null, 
        String $writer = null, 
        String $year = null, 
        String $book = null,
        String $sortBy = null,
        String $sortType = null,
        bool $includeID = false
    )
    {
        return DB::table('books as b')
        ->selectRaw("b.slug, b.title, c.title as category, b.image, b.published_date, CONCAT(w.name,' ',w.lastname) AS author, w.slug as author_slug")
        ->when($includeID, function($query) {
            $query->addSelect(DB::raw("b.id"));
        })
        ->addSelect(DB::raw("(SELECT COUNT(*) FROM book_records tr WHERE tr.book_id = b.id and tr.deleted_at IS NULL) AS existences"))
        ->addSelect(DB::raw("(SELECT COUNT(*) FROM borrowed_books tp LEFT JOIN book_records tr ON tp.book_record_id = tr.id 
        WHERE tr.book_id = b.id AND tp.return_date IS NULL) AS borrowed_books"))
        ->leftJoin('book_categories as c', 'b.book_category_id', '=', 'c.id')
        ->leftJoin('writers as w', 'b.writer_id', '=', 'w.id')
        ->when($category, function($query) use ($category) {
            $query->where('c.slug', $category);
        })
        ->when($writer, function($query) use ($writer) {
            $query->where('w.slug', $writer);
        })
        ->when($year, function($query) use ($year) {
            $query->whereYear('b.published_date', $year);
        })
        ->when($book, function($query) use ($book) {
            $query->where('b.title', 'like', "%$book%");
        })
        ->whereNull('b.deleted_at')
        ->when($sortBy != null && $sortType != null, function($query) use ($sortBy, $sortType) {
            $query->orderBy($sortBy, $sortType);
        })
        ->when($sortBy == null || $sortType == null, function($query) {
            $query->orderBy('b.title');
        })
        ->paginate(10);
    }

    /**
     * Get book
     * 
     * @return book
     */
    public function getBook(String $slug)
    {
        return DB::table('books as b')
        ->selectRaw("b.title, b.image, b.published_date, b.synopsis, c.title as category,
        c.slug as category_slug, CONCAT(w.name,' ',w.lastname) AS author, w.slug as author_slug")
        ->addSelect(DB::raw("(SELECT COUNT(*) FROM book_records tr WHERE tr.book_id = b.id and tr.deleted_at IS NULL) AS existences"))
        ->addSelect(DB::raw("(SELECT COUNT(*) FROM borrowed_books tp LEFT JOIN book_records tr ON tp.book_record_id = tr.id 
        WHERE tr.book_id = b.id AND tp.return_date IS NULL) AS borrowed_books"))
        ->leftJoin('book_categories as c', 'b.book_category_id', '=', 'c.id')
        ->leftJoin('writers as w', 'b.writer_id', '=', 'w.id')
        ->whereNull('b.deleted_at')
        ->where('b.slug', $slug)
        ->first();
    }

    /**
     * Get related books
     * 
     * @return collection
     */
    public function relatedBooks(String $category)
    {
        return DB::table('books as b')
        ->selectRaw("b.slug, b.title, b.image, CONCAT(w.name,' ',w.lastname) AS author, w.slug as author_slug")
        ->addSelect(DB::raw("(SELECT COUNT(*) FROM book_records tr WHERE tr.book_id = b.id and tr.deleted_at IS NULL) AS existences"))
        ->addSelect(DB::raw("(SELECT COUNT(*) FROM borrowed_books tp LEFT JOIN book_records tr ON tp.book_record_id = tr.id 
        WHERE tr.book_id = b.id AND tp.return_date IS NULL) AS borrowed_books"))
        ->leftJoin('book_categories as c', 'b.book_category_id', '=', 'c.id')
        ->leftJoin('writers as w', 'b.writer_id', '=', 'w.id')
        ->whereNull('b.deleted_at')
        ->inRandomOrder()
        ->limit(20)
        ->get();
    }

    /**
     * Get book to edit
     * 
     * @return book
     */
    public function edit(int $id)
    {
        return Book::select([
            'id','writer_id','book_category_id',
            'published_date','synopsis', 'title'
        ])->findOrFail($id);
    }

    /**
     * Update or Create book
     * 
     * @return book
     */
    public function updateOrCreate(int $id = null, FormRequest $book)
    {
        $faker = Factory::create();
        $randomImage = $faker->imageUrl(720, 1080); // upload image from client: TODO

        return Book::updateOrCreate(
            [ 'id' => $id ],
            [
                'writer_id'         => $book->writer_id,
                'book_category_id'  => $book->category_id,
                'published_date'    => $book->published_date,
                'synopsis'          => $book->synopsis,
                'image'             => $randomImage,
                'title'             => $book->title,
                'slug'              => Str::of($book->title)->slug('-')
            ]
        )->makeHidden('deleted_at');
    }

    /**
     * Delete book
     * 
     * @return status
     */
    public function delete(int $id)
    {
        return Book::destroy($id);
    }

}