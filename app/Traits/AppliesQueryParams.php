<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/23/2021
 * Time: 3:40 AM
 */

namespace App\Traits;


use App\Helpers\ArrayHelper;
use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait AppliesQueryParams
{
    public function applyFilters(Request &$request): callable
    {
        return function (Builder $query) use (&$request) {
            $query->when($request->get('id'), function (Builder $query, $id) {

                if (ArrayHelper::isArray($id)) {

                    return $query->whereIn('id', $id);
                }
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
                return $builder->where('type', $type);
            })->when($request->get('search'), function (Builder $builder, $search) {
                $search = StringHelper::lower($search);

                return $builder->where('name', 'ilike', "%" . $search . "%");
            })->when($request->get('name'), function (Builder $builder, $search) {
                $search = StringHelper::lower($search);

                return $builder->where('name', 'ilike', "%" . $search . "%");
            })->when($request->get('product_name'), function (Builder $builder, $search) {
                $search = StringHelper::lower($search);

                return $builder->whereHas('product', function (Builder $query) use ($search) {
                    return $query->where('name', 'ilike', "%" . $search . "%");
                });
            })->when($request->get('product_id'), function (Builder $builder, $productId) {
                if (StringHelper::isInt($productId)) {
                    return $builder->where('product_id', $productId);
                }
            })->when($request->get('category_id'), function (Builder $builder, $categoryId) {
                if (StringHelper::isInt($categoryId)) {
                    return $builder->where(function (Builder $builder) use ($categoryId) {
                        $builder->whereHas('product', function (Builder $builder) use ($categoryId) {
                            $builder->where('category_id', $categoryId);
                        });
                    });
                }
            })->when($request->get('department_id'), function (Builder $builder, $department_id) {
                if (StringHelper::isInt($department_id)) {
                    return $builder->where(function (Builder $builder) use ($department_id) {
                        $builder->whereHas('product', function (Builder $builder) use ($department_id) {
                            $builder->where('department_id', $department_id);
                        });
                    });
                }
            })->when($request->get('status_id'), function (Builder $builder, $statusId) {
                if (StringHelper::isInt($statusId)) {
                    return $builder->where('status_id', $statusId);
                }
            })->when($request->get('status'), function (Builder $builder, $statusId) {
                return $builder->where('status', $statusId);
            })->when($request->get('stock_bin_id'), function (Builder $builder, $statusId) {
                if (StringHelper::isInt($statusId)) {
                    return $builder->where('stock_bin_id', $statusId);
                }
            })->when($request->get('OrUPC'), function (Builder $builder, $upc) {
                if (StringHelper::isInt($upc)) {
                    $builder->orWhere('product_id', $upc);
                }
                return $builder->orWhere
                    ->whereHas('product', function (Builder $query) use ($upc) {
                        $upc = StringHelper::lower($upc);
                        $query->where('UPC', $upc);
                    });
            })->when($request->get('customerName'), function (Builder $builder, $customerName) {
                $search = StringHelper::trimLower($customerName);
                return $builder->whereHas('customer', function (Builder $builder) use ($search) {
                    $builder->where('name', 'ilike', "%" . $search . "%");
                });
            })->when($request->get('customerPhone'), function (Builder $builder, $customerPhone) {
                return $builder->whereHas('customer', function (Builder $builder) use ($customerPhone) {
                    $builder->where('phone', 'like', "%" . $customerPhone . "%");
                });
            })->when($request->get('product_name'), function (Builder $builder, $productName) {

                return $builder->whereHas('product', function (Builder $builder) use ($productName) {
                    $builder->where('name', 'ilike', "%" . $productName . "%");
                });
            })->when($request->get('exclude_id'), function (Builder $query, $exclude_id) {

                return $query->where('id', '!=', $exclude_id);
            })->when($request->get('date_range'), function (Builder $builder, $date_range) use ($request) {
                if ($request->get('apply_on_update',false)) {

                    $builder->whereRaw("updated_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
                }
                else{
                    $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
                }

            })->when($request->get('has_serial'), function (Builder $builder, $has_serial_number) {
                return $builder->whereHas('product', function (Builder $builder) use ($has_serial_number) {
                    $builder->where('has_serial_number', $has_serial_number);
                });
            })->when($request->get('is_sold'), function (Builder $builder, $is_sold) {
                return  $builder->where('is_sold', $is_sold);
            })->when($request->get('quantity'), function (Builder $builder, $quantity) {
                if ($quantity) {
                    $builder->where('quantity', '>', 0);
                }
            });
        };
    }
}