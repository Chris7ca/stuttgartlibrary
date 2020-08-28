<?php

namespace App\Http\Controllers;

use App\Repositories\Books;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    
    private $repository;

    public function __construct(Books $repository)
    {
        $this->middleware(['auth','checkHasRole:librarian']);
        $this->repository = $repository;
    }

    /**
     * Admin view
     * 
     * @return view
     */
    public function adminView()
    {
        return view('app.admin');
    }

}
