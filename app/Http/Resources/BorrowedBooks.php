<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class BorrowedBooks
{

    private $records;

    public function __construct(Collection $records)
    {
        $this->records = $records;
    }

    /**
     * Set fromat to collection
     * 
     * @return collection
     */
    public function format()
    {
        $this->setDaysToExpire();

        return $this->records;
    }

    /**
     * Calculate days to expire
     * 
     * @return void
     */
    private function setDaysToExpire()
    {
        $this->records->each(function ($record, $key) {
            if ( $record->return_date == null ) {
                $deadline = Carbon::create($record->deadline);
                $record->days_to_expire = now()->diffInDays($deadline, false);
            }
            else {
                $record->days_to_expire = null;
            }
        });
    }

}