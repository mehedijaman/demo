<?php

namespace App\Http\Controllers;

use App\Exports\TransactionExport;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Balance;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $transactions = Transaction::all();
        $transactions = Transaction::with(['category'])->where('user_id', Auth::id())->get();

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->get(); // Assuming you have a Category model to fetch categories
        return view('transactions.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate the request
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
        ]);

        $validated = $request->only(['category_id', 'title', 'description', 'amount', 'date']);
        $validated['user_id'] = Auth::id(); // Set the user_id to the authenticated user's ID

        DB::beginTransaction();

        Transaction::create($validated);

        $category = Category::find($validated['category_id']);
        $balance = Balance::firstOrCreate(['user_id' => Auth::id()]);

        if ($category->type === 'income') {
            $balance->increment('current_balance', $validated['amount']);
        } elseif ($category->type === 'expense') {
            $balance->decrement('current_balance', $validated['amount']);
        }
        
        DB::commit();

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        return view('transactions.edit', compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $validated = $request->validated();

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }

    /**
     * Export transactions to Excel.
     */
    public function export()
    {
        return Excel::download(new TransactionExport, 'transactions.xlsx');
    }
}
