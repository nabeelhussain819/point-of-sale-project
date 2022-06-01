<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 4/22/2021
 * Time: 12:34 AM
 */

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait InteractWithProducts
{
    public function loadProducts()
    {
        $this->load(['products' => function (HasMany $belongsTo) {
            $belongsTo->with(['product' => function (BelongsTo $belongsTo) {
                $belongsTo->select(['id', 'name']);
            }]);
        }]);

        return $this;
    }

    public function withFormattedDateTime(){
        $this->setAttribute("dateTime",$this->created_at->format("y/d/m H:i"));

        return $this;
    }

}
