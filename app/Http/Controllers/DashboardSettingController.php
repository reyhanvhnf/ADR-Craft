<?php

namespace App\Http\Controllers;

use App\City;
use App\Category;
use App\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardSettingController extends Controller
{

    public function account()
    {
        $user = Auth::user();
        $provinsi = Province::all(); 
        
        return view('pages.dashboard-account',[
            'user' => $user,
            'provinsi' => $provinsi
        ]);
    }

    public function getCity($id) {
      
        $cities = City::where('province_id','=', $id)->get();
        return json_encode($cities);
    }

    public function update(Request $request, $redirect)
    {
        $data = $request->all();

        $item = Auth::user();

        $item->update($data);

        return redirect()->route($redirect);
    }
}
