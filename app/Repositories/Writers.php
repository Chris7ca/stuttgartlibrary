<?php

namespace App\Repositories;

use App\Models\Writer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class Writers
{

    /**
     * Search in writers
     * 
     * @return collection
     */
    public function search(String $name)
    {
        return Writer::selectRaw("CONCAT(name,' ',lastname) as name, slug")
        ->when(auth()->user()->presenter()->isLibrarian(), function($query) {
            $query->addSelect('id');
        })
        ->whereRaw("CONCAT(name,' ',lastname) LIKE '%$name%'")
        ->limit(10)
        ->get();
    }

    /**
     * Paginated List
     * 
     * @return collection
     */
    public function paginatedList(String $search = null, String $sortBy = null, String $sortType = null)
    {
        return Writer::selectRaw("id, CONCAT(name,' ',lastname) as fullname, name, lastname, country")
        ->when($search !=  null, function($query) use ($search) {
            $query->whereRaw("(
                CONCAT(name,' ',lastname) LIKE '%$search%' OR country LIKE '%$search%'
            )");
        })
        ->when($sortType != null && $sortBy != null, function($query) use ($sortBy, $sortType) {
            $query->orderBy($sortBy, $sortType);
        })
        ->when($sortType == null, function($query) {
            $query->orderBy('fullname', 'asc');
        })
        ->whereNull('deleted_at')
        ->paginate(10);
    }

    /**
     * Update or create Writer
     * 
     * @return Writer
     */
    public function updateOrCreate(int $id = null, FormRequest $writer)
    {
        return Writer::updateOrCreate(
            ['id' => $id],
            [
                'slug'      => Str::slug("{$writer->name} {$writer->lastname}"),
                'name'      => $writer->name,
                'lastname'  => $writer->lastname,
                'country'   => $writer->country
            ]
        )->makeHidden('deleted_at');
    }

    /**
     * Deleted Writer
     * 
     * @return status
     */
    function delete(int $id)
    {
        return Writer::destroy($id);
    }

}