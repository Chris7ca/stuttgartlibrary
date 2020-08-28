<?php

namespace App\Repositories;

use App\Models\User;

class Users
{

    /**
     * Search in users
     * 
     * @return collection
     */
    public function searchByName(String $name)
    {
        return User::selectRaw("id, CONCAT(name,' ',lastname) as name")
        ->whereRaw("CONCAT(name,' ',lastname) LIKE '%$name%'")
        ->limit(10)
        ->get();
    }

}