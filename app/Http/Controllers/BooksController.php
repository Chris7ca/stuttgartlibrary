<?php

namespace App\Http\Controllers;

use App\Repositories\Books;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBook;
use App\Http\Requests\UpdateBook;

class BooksController extends Controller
{

    protected $repository;
    
    public function __construct(Books $repository)
    {
        $this->middleware(['auth','checkHasRole:librarian'])->only([
            'paginatedBookList',
            'storeBook','editBook',
            'updateBook','deleteBook'
        ]);

        $this->repository = $repository;
    }

    /**
     * Recently published books
     * 
     * @return collection
     */
    public function recentlyPublishedBooks()
    {
        return $this->repository->recentlyPublished();
    }

    /**
     * Random books
     * 
     * @return collection
     */
    public function getRandomBooks()
    {
        return $this->repository->randomBooks();
    }

    /**
     * List of books
     * 
     * @return collection
     */
    public function bookList(Request $request)
    {
        $category   = $request->query('category');
        $writer     = $request->query('writer');
        $year       = $request->query('year');

        return $this->repository->paginatedList($category, $writer, $year);
    }

    /**
     * Book view
     * 
     * @return view
     */
    public function showBookView(Request $request, String $slug)
    {
        $book = $this->repository->getBook($slug);

        if ( !$book ) abort(404, 'Book not found');

        return view('app.book', compact('book'));
    }

    /**
     * Related books
     * 
     * @return collection
     */
    public function relatedBooks(Request $request)
    {
        $category = $request->query('category');
        
        return $this->repository->relatedBooks($category);
    }

    /**
     * Search in books
     * 
     * @return collection
     */
    public function searchBooks(Request $request)
    {
        $title = $request->query('find');
        
        return $this->repository->searchByTitle($title);
    }

    /**
     * Book List
     * 
     * @return collection
     */
    public function paginatedBookList(Request $request)
    {
        $book       = $request->query('book');
        $category   = $request->query('category');
        $writer     = $request->query('writer');
        $year       = $request->query('year');
        $sortBy     = $request->query('sortBy');
        $sortType     = $request->query('sortType');

        return $this->repository->paginatedList($category, $writer, $year, $book, $sortBy, $sortType, true);
    }

    /**
     * Store book
     * 
     * @return book
     */
    public function storeBook(StoreBook $request)
    {
        $book = $this->repository->updateOrCreate(null, $request);
        
        return $this->loadAuthorAndCategory($book);
    }

    /**
     * Get book to edit
     * 
     * @return book
     */
    public function editBook(int $id)
    {
        return $this->repository->edit($id);
    }

    /**
     * Update book
     * 
     * @return book
     */
    public function updateBook(UpdateBook $request, int $id)
    {
        $book = $this->repository->updateOrCreate($id, $request);

        return $this->loadAuthorAndCategory($book);
    }

    /**
     * Delete book
     * 
     * @return message
     */
    public function deleteBook(int $id)
    {
        $deleted = $this->repository->delete($id);

        if ( !$deleted ) abort(500, 'An error occurred while deleting book');

        return response('Book deleted');
    }

    /**
     * Load author and category relationships
     * 
     * @return book
     */
    private function loadAuthorAndCategory($model)
    {
        $book = $model->replicate();

        $model->load([
            'author:id,name,lastname',
            'category:id,title'
        ]);
        
        $book->id = $model->id;
        $book->category = $model->category->title;
        $book->author = "{$model->author->name} {$model->author->lastname}";

        return $book;
    }

}
