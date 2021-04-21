<?php

namespace App\Models;

use App\Helpers\ArrayHelper;
use App\Traits\NewCustomers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $telephone
 * @property string $created_at
 * @property string $updated_at
 * @property OrderProduct[] $orderProducts
 */
class Customer extends Model
{
    use NewCustomers;
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'phone', 'telephone', 'created_at', 'updated_at'];
    protected $appends = ['name_phone'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public static function create(Request $request)
    {
        $customer = new Customer();
        $customer->fill($request->all())->save();
        return $customer;
    }

    public function setNameAttribute($value)
    {
        if (ArrayHelper::isArray($value)) {
            $this->attributes['name'] = strtolower($value[0]);
        } else {
            $this->attributes['name'] = strtolower($value);
        }
    }

    public function getNamePhoneAttribute()
    {
        return $this->name . " (" . $this->phone . ")";
    }

    /**
     * @param string $name
     * @param string $phone
     * @return Customer
     */
    public static function basicCreate(string $name, string $phone)
    {
        $customer = new Customer();

        $customer->fill([
            'name' => $name,
            'phone' => $phone
        ]);

        $customer->save();
        return $customer;
    }
}
