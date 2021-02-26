<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $telephone
 * @property string $mailing_address
 * @property string $website
 * @property string $contact_title
 * @property string $contact_number
 * @property string $contact_email
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class Vendor extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name', 'telephone', 'mailing_address', 'website', 'contact_title', 'contact_number', 'contact_email', 'description', 'created_at', 'updated_at'];


    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
