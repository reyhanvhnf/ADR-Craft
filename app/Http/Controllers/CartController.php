<?php

namespace App\Http\Controllers;

use App\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Province;
use App\City;

class CartController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(Request $request)
    {   
        $carts = Cart::with(['product.galleries','user'])
                        ->where('users_id', Auth::user()->id)
                        ->get();
                        
        $user = Auth::user();
        
        $provinsi = Province::all(); 

// dd($carts);
        return view('pages.cart', [
            'carts' => $carts,
            'user' => $user,
            'provinsi' => $provinsi
        ]);
    }

     public function getCity($id) {
        $cities = City::where('province_id','=', $id)->get();
        return json_encode($cities);
    }

    public function getOngkir(Request $request) {
        $data = $request['request'];

        $origin = 455; // Origin kota Tangerang
        $destination = $data['destination'];
        $weight = 1700; // Karena produknya ga punya berat, maka default 1700
        $courier = $data['courier']; 
        
        $response = Http::asForm()->withHeaders([
            'key' => 'b891c30147a00276f0e7c65836414217'
        ])->post('https://api.rajaongkir.com/starter/cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier

        ]);
        
        $cekongkir = $response['rajaongkir']['results'][0]['costs'];
        return $cekongkir;
    }

    public function update(Request $request, $id){
        $cart = Cart::findOrFail($id);
        $cart->update([
            'quantity' => $request->quantity
        ]);
    }
    
    public function delete(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);

        $cart->delete();

        return redirect()->route('cart');
    }
    
    public function success()
    {
        return view('pages.success');
    }
}
