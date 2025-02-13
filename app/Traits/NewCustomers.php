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
use Illuminate\Database\QueryException;

trait NewCustomers
{


    /**
     * @param array $data
     * @return Customer
     * @throws \Exception
     */
    public static function moduleCreate(array $data): Customer
    {
        try {
            if (isset($data['customer_id'])) {
                $isNewCustomer = StringHelper::isInt($data['customer_id'][0]);

                //if customer already exist
                if ($isNewCustomer) {
                    return Customer::find($data['customer_id'][0]);
                }

                $customer = new Customer();

                $customer->fill([
                        'name' => isset($data['customer_id']) ? $data['customer_id'] : '',
                        'phone' => isset($data['customer_phone']) ? $data['customer_phone'] : '',
                        'address' => isset($data['customer_address']) ? $data['customer_address'] : '',
                    ]
                );

                $customer->save();
                return $customer;
            }
        } catch (\Exception $e) {
            if ($e instanceof QueryException) {
                throw  new \Exception("Customer already exists with this number");
            }
        }

    }
}