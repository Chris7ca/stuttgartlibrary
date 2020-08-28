<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BookCategories;
use App\Http\Requests\StoreBookCategory;
use App\Http\Requests\UpdateBookCategory;

class BookCategoriesController extends Controller
{
 
    private $repository;

    public function __construct(BookCategories $repository)
    {
        $this->middleware(['auth','checkHasRole:librarian'])
            ->except('allBookCategories');
            
        $this->repository = $repository;
    }

    /**
     * Get all book categories
     * 
     * @return collection
     */
    public function allBookCategories(Request $request)
    {
        $countBooks = $request->query('countBooks');

        return $this->repository->bookCategories($countBooks);
    }

    /**
     * Categories list
     * 
     * @return collection 
     */
    public function list(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sortBy');
        $sortType = $request->query('sortType');

        return $this->repository->paginatedList($search, $sortBy, $sortType);
    }

    /**
     * Store Category
     * 
     * @return BookCategory
     */
    public function storeCategory(StoreBookCategory $request)
    {
        return $this->repository->updateOrCreate(null, $request);
    }

    /**
     * Update Category
     * 
     * @return BookCategory
     */
    public function updateCategory(UpdateBookCategory $request, int $id)
    {
        return $this->repository->updateOrCreate($id, $request);
    }

    /**
     * Delete Category
     * 
     * @return message
     */
    public function deleteCategory(int $id)
    {
        $deleted = $this->repository->delete($id);

        if ( !$deleted ) abort(500, 'An error occurred while deleting category');

        return response('Category deleted');
    }
    
}
