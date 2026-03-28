<?php

namespace App\Http\Controllers\Admin\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\StoreApp\Products\AddProductRequest;
use App\Http\Requests\Admin\StoreApp\Products\DeleteProductImageRequest;
use App\Http\Requests\Admin\StoreApp\Products\UpdateProductRequest;
use App\Http\Requests\Admin\StoreApp\Products\ValidateProductRequest;
use App\Models\StoreProduct;
use App\Models\StoreProductImage;
use App\Models\User;

class ProductController extends Controller
{
    use FileStorage;

    public function index()
    {
        $language = app()->getLocale();
        $products = StoreProduct::query()->select(
            'store_products.id',
            "users.name AS store_name",
            "store_classifications.name_$language AS classification_name",
            "store_products.name_$language AS name",
            'store_products.image',
            'store_products.price',
            'store_products.sale_price',
            'store_products.quantity',
            'store_products.displayed'
        )
            ->join('users', 'users.id', '=', 'store_products.store_id')
            ->join('store_classifications', 'store_classifications.id', '=', 'store_products.classification_id');

        if (auth()->user()->role_id == 6) {
            $products = $products->where('store_products.store_id', auth()->id());
        }

        $products = $products->paginate(10);

        return view('admin.store-app.products.index', compact('products'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        $languages = ['ar', 'en', 'ur'];
        $stores = User::query()
            ->select('id', 'name')
            ->where('role_id', '6');

        if (auth()->user()->role_id == 6) {
            $stores = $stores->where('id', auth()->id());
        }

        $stores = $stores->get();

        return view('admin.store-app.products.create', compact('languages', 'stores'));
    }

    public function store(AddProductRequest $request)
    {
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["name_$lang"] = $request->input("name_$lang");
            $data["description_$lang"] = $request->input("description_$lang");
        }

        $data = array_merge($data, [
            'store_id' => $request->store_id,
            'classification_id' => $request->classification_id,
            'image' => $this->uploadFile($request, 'store_products'),
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'quantity' => $request->quantity,
            'displayed' => ($request->show) ? 1 : 0
        ]);

        $product = StoreProduct::create($data);

        $paths = $this->uploadMultipleFiles($request, 'store_products');
        $dataFiles = [];

        foreach ($paths as $path) {
            $dataFiles[] = [
                'product_id' => $product->id,
                'path' => $path
            ];
        }

        StoreProductImage::insert($dataFiles);

        session()->flash('success', __('messages.add_product'));
        return redirect(route('store_app.admin.products.create'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $product = StoreProduct::query()->with('images');
        $languages = ['ar', 'en', 'ur'];
        $stores = User::query()->select('id', 'name')->where('role_id', '6');

        if (auth()->user()->role_id == 6) {
            $product->where('store_id', auth()->id());
            $stores->where('id', auth()->id());
        }

        $product = $product->findOrFail($id);
        $stores = $stores->get();

        return view('admin.store-app.products.edit', compact('product', 'languages', 'stores'));
    }

    public function update(UpdateProductRequest $request)
    {
        $product = StoreProduct::findOrFail($request->product_id);
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["name_$lang"] = $request->input("name_$lang");
        }

        $data = array_merge($data, [
            'store_id' => $request->store_id,
            'classification_id' => $request->classification_id,
            'image' => $this->uploadFile($request, 'store_products', $product),
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'quantity' => $request->quantity,
            'displayed' => ($request->show) ? 1 : 0
        ]);

        $product->update($data);

        $paths = $this->uploadMultipleFiles($request, 'store_products');
        $dataFiles = [];

        foreach ($paths as $path) {
            $dataFiles[] = [
                'product_id' => $product->id,
                'path' => $path
            ];
        }

        StoreProductImage::insert($dataFiles);

        session()->flash('success', __('messages.edit_product'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function display(ValidateProductRequest $request)
    {
        $product = StoreProduct::findOrFail($request->product_id);

        $product->update([
            'displayed' => ($product->displayed == 1) ? 0 : 1
        ]);

        session()->flash('success', ($product->displayed == 1) ? __('messages.show_product') : __('messages.hide_product'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateProductRequest $request)
    {
        $product = StoreProduct::findOrFail($request->product_id);
        $product->delete();
        $this->deleteFile($product->image);

        session()->flash('success', __('messages.delete_product'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroyImage(DeleteProductImageRequest $request)
    {
        $image = StoreProductImage::query();

        if (auth()->user()->role_id == 6) {
            $image = $image->whereHas('product', function ($query) {
                $query->where('store_id', auth()->id());
            });
        }

        $image = $image->find($request->image_id);

        if (!$image) {
            return response()->json(['message' => __('messages.something_went_wrong')], 400);
        }

        $this->deleteFile($image->path);
        $image->delete();

        return response()->json(['message' => __('messages.delete_product_image')]);
    }
}
