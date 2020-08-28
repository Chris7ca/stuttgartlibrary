<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Writers;
use App\Http\Requests\StoreWriter;
use App\Http\Requests\UpdateWriter;

class WritersController extends Controller
{
    
    protected $repository;

    public function __construct(Writers $repository)
    {
        $this->middleware(['auth','checkHasRole:librarian'])
            ->except('searchWriter');
            
        $this->repository = $repository;
    }

    /**
     * Search writer
     * 
     * @return collection
     */
    public function searchWriter(Request $request)
    {
        $name = str_replace(' ', '%', $request->query('find'));

        return $this->repository->search($name);
    }

    /**
     * Writers list
     * 
     * @return collection
     */
    public function writerList(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sortBy');
        $sortType = $request->query('sortType');

        return $this->repository->paginatedList($search, $sortBy, $sortType);
    }

    /**
     * Store writer
     * 
     * @return Writer
     */
    public function storeWriter(StoreWriter $request)
    {
        $writer = $this->repository->updateOrCreate(null, $request);
        $writer->fullname = "{$writer->name} {$writer->lastname}";

        return $writer;
    }

    /**
     * Update writer
     * 
     * @return Writer
     */
    public function updateWriter(UpdateWriter $request, int $id)
    {
        $writer = $this->repository->updateOrCreate($id, $request);
        $writer->fullname = "{$writer->name} {$writer->lastname}";

        return $writer;
    }

    /**
     * Delete writer
     * 
     * @return message
     */
    public function deleteWriter(int $id)
    {
        $deleted = $this->repository->delete($id);

        if ( !$deleted ) abort(500, 'An error occurred while deleting writer');

        return response('Writer deleted');
    }

}
