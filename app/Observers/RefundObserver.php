<?php

namespace App\Observers;

use App\Models\Refund;

class RefundObserver
{
    public function creating(Refund $refund)
    {
        //dd($refund);

        //Validate k order mai itne paise hain bhi k nhi
        //        //Create new order
        //        //
    }
}
