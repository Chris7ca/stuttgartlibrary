<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BookRecords;
use App\Repositories\BorrowedBooks;
use App\Http\Requests\StoreBookRecord;
use App\Http\Requests\UpdateBookRecord;

class BookRecordsController extends Controller
{
    
    private $repository;

    public function __construct(BookRecords $repository)
    {
        $this->middleware(['auth','checkHasRole:librarian']);
        $this->repository = $repository;
    }

    /**
     * Get all records
     * 
     * @return collection
     */
    public function allRecords(int $id, BorrowedBooks $repo)
    {
        $records = $this->repository->all($id);

        return collect($records->whereNotIn('id', 
            $repo->unavailableRecords($records->pluck('id')->toArray())
                ->pluck('record_id')
        ))->values();
    }

    /**
     * Store record
     * 
     * @return collection
     */
    public function storeRecord(StoreBookRecord $request, int $id)
    {
        return $this->repository->updateOrCreate($id, null, $request);
    }

    /**
     * Update Record
     * 
     * @return collection
     */
    public function updateRecord(UpdateBookRecord $request, int $book, int $id)
    {
        return $this->repository->updateOrCreate($book, $id, $request);
    }

    /**
     * Delete record
     * 
     * @return message
     */
    public function deleteRecord(int $book, int $id)
    {
        $deleted = $this->repository->delete($id);

        if ( !$deleted ) abort(500, 'An error occurred while deleting this record');

        return response('Records deleted');
    }

}
