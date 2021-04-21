<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 4/22/2021
 * Time: 12:34 AM
 */

namespace App\Traits;


use App\Helpers\StringHelper;
use App\Models\Customer;

trait NewCustomers
{

    public static function moduleCreate(array $data): Customer
    {
        if (isset($data['customer_id'])) {
            $isNewCustomer = StringHelper::isInt($data['customer_id'][0]);
            if ($isNewCustomer) {
                return Customer::find($data['customer_id'][0]);
            }
            $customer = new Customer();

            $customer->fill([
                    'name' => isset($data['customer_name']) ? $data['customer_name'] : '',
                    'phone' => isset($data['customer_name']) ? $data['customer_phone'] : '',
                    'address' => isset($data['customer_name']) ? $data['customer_address'] : '',
                ]
            );
            $customer->save();
            return $customer;
        }
    }
}