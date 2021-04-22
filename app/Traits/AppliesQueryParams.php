<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/23/2021
 * Time: 3:40 AM
 */

namespace App\Traits;


use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait AppliesQueryParams
{
    public function applyFilters(Request &$request): callable
    {
        return function (Builder $query) use (&$request) {
            $query->when($request->get('id'), function (Builder $query, $id) {
                return $query->where('id', (int)$id);
            })->when($request->get('active'), function (Builder $query, $active) {
                return $query->where('active', $active);
            })->when($request->get('categories'), function (Builder $query, $categories) {
                if (is_array($categories)) {
                    return $query->whereHas('categories', function (Builder $query) use ($categories) {
                        $query->whereIn('category_id', $categories);
                    });
                }
                return $query->whereHas('categories', function (Builder $query) use ($categories) {
                    $query->where('category_id', $categories);
                });
            })->when($request->get('type'), function (Builder $builder, $type) {
                return    $builder->where('type', $type);
            })->when($request->get('search'), function (Builder $builder, $search) {
                $search = strtolower($search);
                return $builder->where('name', 'like', "%".$search."%");
            })->when($request->get('product_id'), function (Builder $builder, $productId) {
                if (StringHelper::isInt($productId)) {
                    return $builder->where('product_id', $productId);
                }
            })->when($request->get('OrUPC'), function (Builder $builder, $upc) {
                if (StringHelper::isInt($upc)) {
                    $builder->orWhere('product_id', $upc);
                }
                return $builder->orWhere
                    ->whereHas('product', function (Builder $query) use ($upc) {
                        $query->where('UPC', $upc);
                    });
            })->when($request->get('customerName'), function (Builder $builder, $customerName) {
                $search = StringHelper::trimLower($customerName);
                return $builder->whereHas('customer', function (Builder $builder) use ($search) {
                    $builder->where('name', 'like', "%" . $search . "%");
                });
            })
            ->when($request->get('customerPhone'), function (Builder $builder, $customerPhone) {

                return $builder->whereHas('customer', function (Builder $builder) use ($customerPhone) {
                    $builder->where('phone', 'like', "%" . $customerPhone . "%");
                });
            })->when($request->get('product_name'), function (Builder $builder, $productName) {

                    return $builder->whereHas('product', function (Builder $builder) use ($productName) {
                        $builder->where('name', 'like', "%" . $productName . "%");
                    });
                });
        };
    }
}