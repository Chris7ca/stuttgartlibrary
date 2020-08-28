<?php

namespace App\Http\Controllers;

use App\Repositories\Users;
use Illuminate\Http\Request;
use App\Repositories\Library;

class LibraryController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','checkHasRole:user'])
            ->only(['usersBooks','recordsOfUser']);
    }
    
    /**
     * Home view
     * 
     * @return view
     */
    public function homeView()
    {
        return view('app.home');
    }

    /**
     * Search in library
     * 
     * @return collections
     */
    public function search(Request $request, Library $repository)
    {
        $queryString = str_replace(' ', '%', $request->query('queryString'));

        return $repository->search($queryString);
    }

    /**
     * Library view
     * 
     * @return view
     */
    public function libraryView()
    {
        return view('app.library');
    }

    /**
     * View of user books
     * 
     * @return view
     */
    public function usersBooks(Request $request, Library $repository)
    {
        $records = $repository->usersBooks(auth()->user()->id);

        return view('app.users', compact('records'));
    }

    /**
     * Get records of user
     * 
     * @return collection
     */
    public function recordsOfUser(Request $request, Library $repository)
    {
        return $repository->usersBooks(auth()->user()->id);
    }

}
