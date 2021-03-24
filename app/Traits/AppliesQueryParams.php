<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/23/2021
 * Time: 3:40 AM
 */

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait AppliesQueryParams
{
    public function applyFilters(&$request): callable
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
            })->when($request->get('type'),function (Builder $builder,$type){
                return    $builder->where('type', $type);
            })->when($request->get('search'), function (Builder $builder, $search) {
                $search = strtolower($search);
                return $builder->where('name', 'like', "%".$search."%");
            })->when($request->get('product_id'), function (Builder $builder, $productId) {
                return $builder->where('product_id', $productId);
            });
        };
    }
}