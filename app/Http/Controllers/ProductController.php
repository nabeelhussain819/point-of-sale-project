<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Models\DevicesTypesBrandsProduct;
use App\Models\Product;
use App\Models\ProductSerialNumbers;
use App\Models\StockBin;
use App\Models\Store;
use App\Scopes\ProductRepairScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cost' => 'required|numeric|min:0',
            'retail_price' => 'required|numeric|min:0',
            'min_price' => 'required|numeric|min:0',
        ]);
        $product = new Product();
        $product->guid = Str::uuid();
        $product->fill($request->all())->save();
        return redirect('product-management/products/create')->with('success', 'Product Added');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        return view('admin.products.edit', ['product' => Product::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required',
            'cost' => 'required|numeric|min:0',
            'retail_price' => 'required|numeric|min:0',
            'min_price' => 'required|numeric|min:0',
        ]);

        $product = Product::find($id);

        //   $product->upc = $request->get('UPC');
        $product->update($request->all());
        return redirect()->back()->with('success', "$product->name Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $product = Product::find($id);
        $product->delete();
        return redirect()->back()->with('success', 'Product Deleted');
    }

    public function deviceBrand(Request $request)
    {
        return Product::select(Product::defaultSelect())
            ->whereExists(function (QueryBuilder $query) use ($request) {
                return $query->from('devices_types_brands_products')
                    ->where('brand_id', $request->get('brand_id'))
                    ->where('device_type_id', $request->get('device_type_id'))
                    ->whereColumn('devices_types_brands_products.product_id', 'products.id');
            })->get();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function all(Request $request)
    {
        return Product::where($this->applyFilters($request))
            ->when(StringHelper::isValueTrue($request->get('isRepair')), function (Builder $builder) {
                $builder->withoutGlobalScope(new ProductRepairScope());
            })
            ->paginate($this->pageSize);
    }

    //the purpose of this method iis only temp hot fix showing all product into purchase order will fix in future properly on the front end level and use the same all() method
    public function getAll(Request $request)
    {
        return Product::select(['id', 'name', 'UPC', 'retail_price'])->where($this->applyFilters($request))
            ->when(StringHelper::isValueTrue($request->get('isRepair')), function (Builder $builder) {
                $builder->withoutGlobalScope(new ProductRepairScope());
            })->get();
    }

    public function associateDeviceBrand(Request $request)
    {
        $device = DevicesTypesBrandsProduct::all();
        return view('admin.products.associate_product', ['device' => $device]);
    }

    public function getSerials(Request $request, Product $product)
    {
        return ProductSerialNumbers::getByStoreId($product->id, Store::currentId())
            ->where($this->applyFilters($request))
            ->where('return_to_vendor', false)
            ->paginate($this->pageSize);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Facade\FlareClient\Http\Exceptions\NotFound
     */
    public function validateSerial(Request $request)
    {
        ProductSerialNumbers::isAvailable($request->get('product_id'), $request->get('serial_no'));
        return $this->genericResponse(true, "success");
    }

    public function serialTracking(ProductSerialNumbers $productSerialNumbers)
    {
        return $productSerialNumbers->load(['track']);
    }


}
