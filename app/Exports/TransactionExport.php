<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransactionExport implements FromCollection
{
    public function collection(): Collection
    {
        return Transaction::where('user_id', Auth::id())->get();
    }
}
