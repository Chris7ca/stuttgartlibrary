<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Models\BookCategory;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Resources\BookCategory as BookCategoryResource;

class BookCategories
{

    /**
     * Get all 
     * 
     * @return collection
     */
    private function all($countBooks)
    {
        return BookCategory::select(['id','slug','title','description'])
        ->when($countBooks != null, function($query) {
            $query->withCount('books');
        })
        ->orderBy('title')
        ->get();
    }

    /**
     * All categories
     * 
     * @return collection
     */
    public function bookCategories(bool $countBooks = null)
    {
        $bookCategories = $this->all($countBooks);

        if ($countBooks != null) {
            return $bookCategories->makeHidden('id')
            ->toArray();
        }

        return $bookCategories;
    }

    /**
     * Paginated categories
     * 
     * @return collection
     */
    public function paginatedList(String $search = null, String $sortBy = null, String $sortType = null)
    {
        return BookCategory::select('id', 'title', 'description')
        ->when($search != null, function($query) use ($search) {
            $query->where('title', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        })
        ->when($sortType != null && $sortBy != null, function($query) use ($sortType) {
            $query->orderBy('title', $sortType);
        })
        ->when($sortType == null, function($query) {
            $query->orderBy('title', 'asc');
        })
        ->paginate(10);
    }

    /**
     * Update or Create Book Category
     * 
     * @return BookCategory
     */
    public function updateOrCreate(int $id = null, FormRequest $category)
    {
        return BookCategory::updateOrCreate(
            ['id' => $id],
            [
                'title' => $category->title,
                'slug'  => Str::of($category->title)->slug('-'),
                'description' => $category->description
            ]
        )->makeHidden('deleted_at');
    }

    /**
     * Delete Book Category
     * 
     * @return BookCategory
     */
    public function delete(int $id)
    {
        return BookCategory::destroy($id);
    }

}