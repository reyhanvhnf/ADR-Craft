<?php

namespace App\Http\Controllers;
use App\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ProductRequest;
use App\ProductGallery;

class DashboardProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['galleries'])->get();
        return view('pages.dashboard-product', [
            'products' => $products
        ]);
    }
   
    public function details(Request $request, $id)
    {
        $product = Product::with(['galleries'])->findOrFail($id);
        
        // dd($product);
        return view('pages.dashboard-product-detail', [
            'product' => $product
        ]);
    }

    public function uploadGallery(Request $request)
    {
        $data = $request->all();

        $data['photos'] = $request->file('photos')->store('assets/product', 'public'); // jalankan php artisan storage:link 

        ProductGallery::create($data);

        return redirect()->route('dashboard-product-details', $request->products_id);
    }

    public function deleteGallery(Request $request, $id)
    {
        $item = ProductGallery::findOrFail($id);
        $item->delete();

        return redirect()->route('dashboard-product-details', $item->products_id);
    }
   
    public function create()
    {
        return view('pages.dashboard-product-create');
    }
    
    public function store(ProductRequest $request)
    {
        $data = $request->all();

        $data['slug'] = Str::slug($request->name);
        $product = Product::create($data);

        $gallery = [
            'products_id' => $product->id,
            'photos' => $request->file('photos')->store('assets/product','public')
        ];

        ProductGallery::create($gallery);

        return redirect()->route('dashboard-product');
    }

    public function update(productRequest $request, $id)
    {
        $data = $request->all();

        $item = Product::findOrFail($id);

        $data['slug'] = Str::slug($request->name);  

        $item->update($data);

        return redirect()->route('dashboard-product');
    }
}
