<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreBorrow;
use App\Repositories\BorrowedBooks;
use App\Http\Resources\BorrowedBooks as BorrowedBooksTransformer;

class BorrowedBooksController extends Controller
{
    
    private $repository;

    public function __construct(BorrowedBooks $repository)
    {
        $this->middleware(['auth','checkHasRole:librarian']);
        $this->repository = $repository;
    }

    /**
     * Borrowed book list
     * 
     * @return collection
     */
    public function list(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sortBy');
        $sortType = $request->query('sortType');
        $exclude = $request->query('exclude');

        $pagination = $this->repository->paginatedList($search, $exclude, $sortBy, $sortType);
        
        $data = $pagination->getCollection();

        $transformer = new BorrowedBooksTransformer($data);

        $pagination->setCollection($transformer->format());
        
        return $pagination;
    }

    /**
     * Store borrow
     * 
     * @return message
     */
    public function storeBorrow(StoreBorrow $request)
    {
        $stored = $this->repository->store($request);

        if (!$stored) {
            abort(500, 'An error occurred while save information');
        }

        return response('Record was created');
    }

    /**
     * Store return book
     * 
     * @return message
     */
    public function giveBackBook(int $id)
    {
        $status = $this->repository->returnBook($id);
        
        if (!$status) {
            abort(500, 'An error occurred while save information');
        }

        return response('Information saved');
    }

}
