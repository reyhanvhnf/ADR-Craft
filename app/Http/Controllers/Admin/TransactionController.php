<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\Transaction;
use App\TransactionDetail;
use App\Exports\RekapEkspor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    if (request()->ajax()) {
      $query = Transaction::with('user');
      return $this->renderDataTable($query);
    }
    return view('pages.admin.transaction.index');
  }

  public function rekap(Request $request)
  {
    if (request()->ajax()) {
      $dariTanggal = date($request->dari);
      $keTanggal = date($request->ke);
      return $this->eksporExcel($dariTanggal, $keTanggal);
    }
  }

  public function filter(Request $request)
  {
    $dariTanggal = date($request->dari);
    $keTanggal = date($request->ke);
    $dataFilter = Transaction::with('user')->whereBetween('created_at', [$dariTanggal . '%', $keTanggal . '%']);
    return $this->renderDataTable($dataFilter);
  }

  public function ubahBulan($bulan)
  {
    if ($bulan == "Januari") {
      return '01';
    } else if ($bulan == "Februari") {
      return '02';
    } else if ($bulan == "Maret") {
      return '03';
    } else if ($bulan == "April") {
      return '04';
    } else if ($bulan == "Mei") {
      return '05';
    } else if ($bulan == "Juni") {
      return '06';
    } else if ($bulan == "Juli") {
      return '07';
    } else if ($bulan == "Agustus") {
      return '08';
    } else if ($bulan == "September") {
      return '09';
    } else if ($bulan == "Oktober") {
      return '10';
    } else if ($bulan == "November") {
      return '11';
    } else {
      return '12';
    }
  }

  public function getDataRekapBulanan($dari, $ke)
  {
    $dataRekap['transaksi'] = Transaction::with('user')->where([
      ['created_at', '>=', $dari],
      ['created_at', '<=', $ke]
    ])->orderBy('created_at', 'ASC')->get();
    return json_encode($dataRekap);
  }

  public function eksporExcel($dari, $ke)
  {
    return Excel::download(new RekapEkspor($dari, $ke), 'Rekap Penghasilan ' . $dari . ' to ' . $ke . '.xlsx');
  }

  public function renderDataTable($query)
  {
    return DataTables::of($query)
      ->addColumn('action', function ($item) {
        return '
              <div class="btn-group">
                  <div class="dropdown">
                      <button class="btn btn-primary dropdown-toggle mr-1 mb-1"
                          type="button"
                          data-toggle="dropdown">
                          Action
                      </button>
                      <div class="dropdown-menu">
                          <a class="dropdown-item" href="' . route('transaction.edit', $item->id) .  '">
                          Edit
                          </a>
                          <form action="' . route('transaction.destroy', $item->id) . '" method="POST">
                              ' . method_field('delete') . csrf_field() . '
                              <button type="submit" class="dropdown-item text-danger">
                                  Delete
                              </button>
                          </form>
                      </div>
                  </div>
              </div>
          ';
      })
      ->rawColumns(['action'])
      ->make();
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
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
    $item = Transaction::findOrFail($id);
    $TD = TransactionDetail::where(['transactions_id' => $id])->first();

    return view('pages.admin.transaction.edit', [
      'item' => $item,
      'td' => $TD
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    // Update Transaction
    $data = $request->all();
    $item = Transaction::findOrFail($id);
    $item->update($data);

    if ($data['transaction_status'] == "SHIPPING") {
      $TransactionDetails = TransactionDetail::where(['transactions_id' => $id])->get();
      // Update Resi
      $UpdateResi = Transaction::findOrFail($id);
      $UpdateResi->update([
        'resi' => $data['resi']
      ]);

      foreach ($TransactionDetails as $TD) {
        // Update stock
        $item = Product::findOrFail($TD->products_id);
        $item->update([
          'stock' => $item->stock - $TD->quantity
        ]);
      }

    } else if ($data['transaction_status'] == "PENDING") {
      $TransactionDetails = TransactionDetail::where(['transactions_id' => $id])->get();

      foreach ($TransactionDetails as $TD) {
        $item = Product::findOrFail($TD->products_id);
        $item->update([
          'stock' => $item->stock + $TD->quantity
        ]);
      }
    }
      

    return redirect()->route('transaction.index');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $item = transaction::findOrFail($id);
    $item->delete();

    return redirect()->route('transaction.index');
  }
}
