<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TransactionDetail;
use Illuminate\Support\Facades\Auth;


class DashboardTransactionController extends Controller
{
    public function index()
    {
        $transaction = TransactionDetail::with(['transaction.user','product.galleries'])
                            ->whereHas('transaction', function($transaction){
                                $transaction->where('users_id', Auth::user()->id);
                            })->orderBy('id','desc')->get();
                            
        return view('pages.dashboard-transactions',[
            'transaction' => $transaction
        ]);
    }
    public function details(Request $request, $id)
    {
        $transaction = TransactionDetail::with(['transaction.user','product.galleries'])
                            ->findOrFail($id);
        //dd($transactions)
        
        return view('pages.dashboard-transactions-details',[
            'transaction' => $transaction
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        // dd ($data)

        $item = TransactionDetail::findOrFail($id);

        $item->update($data);

        return redirect()->route('dashboard-transaction-details', $id);
    }
}