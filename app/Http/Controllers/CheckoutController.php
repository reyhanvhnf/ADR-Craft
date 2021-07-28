<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Cart;
use App\Transaction;
use App\TransactionDetail;

use Exception;

use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        // dd($request);
        // Save users data
        $user = Auth::user();
        $user->update($request->except('total_price'));

        // process Checkout
        $code = 'STORE-' . mt_rand(000000, 999999);
        $carts = Cart::with(['product', 'user'])
            ->where('users_id', Auth::user()->id)
            ->get();

        // Hitung total price
        $subtotal = 0;
        $totalPrice = 0;
        foreach ($carts as $cart) {
            $subtotal += $cart->quantity * $cart->product->prices;
        }
        // Tambah ongkir
        $totalPrice = $subtotal + $request->ongkir;
        // gitu aja

         if($subtotal == 0){
            return redirect('/');
        }
        // Transaction create
        $transaction = Transaction::insertGetId([
            'code' => $code,
            'users_id' => Auth::user()->id,
            'sub_total' => $subtotal,
            'shipping_price' => $request->ongkir,
            'total_price' => $totalPrice,
            'transaction_status' => 'PENDING',
            'courier' => $request->couriers, 
            'service' => $request->services, 
            'resi' => '',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        foreach ($carts as $cart) {
            // Tambahkan transaksi detail

            TransactionDetail::create([
                'transactions_id' => $transaction,
                'products_id' => $cart->product->id,
                'prices' => $cart->product->prices,
                'quantity' => $cart->quantity
            ]);
        }

        // Delete Cart Data
        Cart::with(['product', 'user'])
            ->where('users_id', Auth::user()->id)
            ->delete();

        // Set your Merchant Server Key
        Config::$serverKey = config('services.midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = config('services.midtrans.isProduction');
        // Set sanitization on (default)
        Config::$isSanitized = config('services.midtrans.isSanitized');
        // Set 3DS transaction for credit card to true
        Config::$is3ds = config('services.midtrans.is3ds');

        // Buat array untuk dikirm ke midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => $code,
                'gross_amount' => (int) $totalPrice,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'enabled_payments' => [
                'gopay', 'bank_transfer'
            ],
            'vt_web' => []

        ];

        try {
            // Get Snap Payment Page URL
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;

            // Redirect to Snap Payment Page
            return redirect($paymentUrl);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function midtranscancel()
    {
        return view('pages.midtrans.cancel');
    }

    public function midtransfinish(Request $request)
    {
        $code = $request->order_id;
        //pakai $code soalnya takut di pakai lagi kodenaya
        $db = Transaction::where('code',$code)->first();
        
        // return $db;
        return view('pages.midtrans.selesai',compact('db')); 
        //dah tinggal get aja di viewnya lanjut, diviewnya ya ?
        //iya jadi nggak usah redirect , bedain tulisan aja 
    }

    public function midtransunfinish()
    {
        //disini ya? $code = apa tadi
        //bukan, di finish
        return view('pages.midtrans.gagal');
    }

    public function midtranserror()
    {
        return view('pages.midtrans.error');
    }

    public function callback(Request $request)
    {
        
        $transaction = $request->transaction_status;
        $fraud = $request->fraud_status;

        // Storage::put('file.txt', $transaction);
        if ($transaction == 'capture') {
            if ($fraud == 'challenge') {
              // TODO Set payment status in merchant's database to 'challenge'
              
                $update = Transaction::where('code',$request->order_id)->first();
                $update->status_pay = 'FAILED';
                $update->transaction_status = 'PENDING';
                $update->save();
            return;
              
            }else if ($fraud == 'accept') {
              // TODO Set payment status in merchant's database to 'success'
              
                $update = Transaction::where('code',$request->order_id)->first();
                $update->status_pay = 'SUCCESS';
                $update->transaction_status = 'PROCESS';
                $update->save();
            return;
              
            }
        }else if ($transaction == 'cancel') {
            if ($fraud == 'challenge') {
              // TODO Set payment status in merchant's database to 'failure'
              
                $update = Transaction::where('code',$request->order_id)->first();
                $update->status_pay = 'FAILED';
                $update->transaction_status = 'PENDING';
                $update->save();
            return;
              
            }else if ($fraud == 'accept') {
              // TODO Set payment status in merchant's database to 'failure'
              
                $update = Transaction::where('code',$request->order_id)->first();
                $update->status_pay = 'CANCEL';
                $update->transaction_status = 'PENDING';
                $update->save();
            return;
            }
        }else if ($transaction == 'deny') {
      // TODO Set payment status in merchant's database to 'failure'
              
            $update = Transaction::where('code',$request->order_id)->first();
            $update->status_pay = 'FAILED';
            $update->transaction_status = 'PENDING';
            $update->save();
            return;
              
        }else if($transaction == 'pending') {
            
                $update = Transaction::where('code',$request->order_id)->first();
                $update->status_pay = 'PENDING';
                $update->transaction_status = 'PENDING';
                $update->save();
            return;
        }else if($transaction == 'expire') {
            
            $update = Transaction::where('code',$request->order_id)->first();
            $update->status_pay = 'EXPIRED';
            $update->transaction_status = 'PENDING';
            $update->save();
            return;
        }else if($transaction == 'accept') {
            
            $update = Transaction::where('code',$request->order_id)->first();
            $update->status_pay = 'SUCCESS';
            $update->transaction_status = 'PROCESS';
            $update->save();
            return;
        }else if($transaction == 'settlement') {
            
            $update = Transaction::where('code',$request->order_id)->first();
            $update->status_pay = 'SUCCESS';
            $update->transaction_status = 'PROCESS';
            $update->save();
            return;
        }
        echo json_encode('berhasil');
    }
}
