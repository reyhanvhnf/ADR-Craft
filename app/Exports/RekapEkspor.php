<?php

namespace App\Exports;

use App\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;

class RekapEkspor implements FromCollection
{
    private $dari;
    private $ke;

    public function __construct($d, $k){
        $this->dari = $d;
        $this->ke = $k;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Transaction::with('user')->whereBetween('created_at', [$this->dari.'%', $this->ke.'%'])->orderBy('created_at', 'ASC')->get();
    }
}
