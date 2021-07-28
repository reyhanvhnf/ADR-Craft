<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\ProductGallery;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use illuminate\Support\Facades\Storage;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with(['galleries'])->get();

        return view('pages.admin.product.dashboard-product', [
            'products' => $products
        ]);    
    }


    public function details(Request $request, $id)
    {
        $product = Product::with(['galleries'])->findOrFail($id);
        
        return view('pages.admin.product.dashboard-product-detail', [
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.admin.product.dashboard-product-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $data = $request->all();

        $data['slug'] = Str::slug($request->name);
        $product = Product::create($data);
        

        $gallery = [
            'products_id' => $product->id_product,
            'photos' => $request->file('photos')->store('assets/product','public')
        ];

        ProductGallery::create($gallery);

        return redirect()->route('dashboard-product')->with('toast_success', 'Produk Berhasil Ditambahkan!');;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(productRequest $request, $id)
    {   
        $data = $request->all();

        $item = Product::findOrFail($id);

        $data['slug'] = Str::slug($request->name);  

        $item->update($data);

        return redirect()->route('dashboard-product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = product::findOrFail($id);
        $item->delete();

        return redirect()->route('dashboard-product');  
    }
}
