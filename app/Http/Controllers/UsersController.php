<?php

namespace App\Http\Controllers;

use App\Repositories\Users;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    
    private $repository;

    public function __construct(Users $repository)
    {
        $this->middleware(['auth','checkHasRole:librarian']);
        $this->repository = $repository;
    }

    /**
     * Users search
     * 
     * @return collection
     */
    public function searchUsers(Request $request)
    {
        $name = $request->query('find');

        return $this->repository->searchByName($name);
    }

}
