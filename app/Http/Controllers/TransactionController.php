<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\BankAccount;
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $query = Transaction::with('bankAccount')->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $transactions = $query->get();

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $bankAccounts = BankAccount::all();
        return view('transactions.create', compact('bankAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string'
        ]);

        Transaction::create($request->all());
        return redirect()->route('transactions.index')->with('success', 'บันทึกรายการสำเร็จ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $bankAccounts = BankAccount::all();
        return view('transactions.edit', compact('transaction', 'bankAccounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'bank_account_id' => 'required',
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category' => 'required',
            'amount' => 'required|numeric',
            'note' => 'nullable',
        ]);

        $transaction->update($request->all());
        return redirect()->route('transactions.index')->with('success', 'แก้ไขรายการสำเร็จ');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'ลบรายการสำเร็จ');
    }

    public function calculateTax($year)
    {
        $totalIncome = Transaction::whereYear('date', $year)
            ->where('type', 'income')
            ->sum('amount');

        $taxRates = [
            [0, 150000, 0],
            [150001, 300000, 0.05],
            [300001, 500000, 0.10],
            [500001, 750000, 0.15],
            [750001, 1000000, 0.20],
            [1000001, 2000000, 0.25],
            [2000001, 5000000, 0.30],
            [5000001, PHP_INT_MAX, 0.35],
        ];

        $tax = 0;
        foreach ($taxRates as [$min, $max, $rate]) {
            if ($totalIncome > $min) {
                $incomeInBracket = min($totalIncome, $max) - $min;
                $tax += $incomeInBracket * $rate;
            }
        }

        return view('transactions.tax', compact('year', 'totalIncome', 'tax'));
    }
}
