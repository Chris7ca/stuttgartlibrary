<?php

namespace App\Presenters;

use App\Models\User;

class UserPresenter
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function userName()
    {  
        return "{$this->user['name']} {$this->user['lastname']}";
    }

    public function isLibrarian()
    {
        return $this->user['role'] == 'librarian';
    }

}