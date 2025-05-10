<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\BankAccount;
use App\Models\Category;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $query = Transaction::with(['bankAccount', 'category'])->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $transactions = $query->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $bankAccounts = BankAccount::all();
        $categories = Category::all();
        return view('transactions.create', compact('bankAccounts', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        $transaction = Transaction::create($validated);

        // Update bank account balance
        $bankAccount = BankAccount::findOrFail($validated['bank_account_id']);
        if ($validated['type'] === 'income') {
            $bankAccount->balance += $validated['amount'];
        } else {
            $bankAccount->balance -= $validated['amount'];
        }
        $bankAccount->save();

        return redirect()->route('transactions.index')->with('success', 'เพิ่มรายการสำเร็จ');
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
        $categories = Category::all();
        return view('transactions.edit', compact('transaction', 'bankAccounts', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        // Revert old transaction's effect on bank account balance
        $oldBankAccount = BankAccount::findOrFail($transaction->bank_account_id);
        if ($transaction->type === 'income') {
            $oldBankAccount->balance -= $transaction->amount;
        } else {
            $oldBankAccount->balance += $transaction->amount;
        }
        $oldBankAccount->save();

        // Update transaction
        $transaction->update($validated);

        // Apply new transaction's effect on bank account balance
        $newBankAccount = BankAccount::findOrFail($validated['bank_account_id']);
        if ($validated['type'] === 'income') {
            $newBankAccount->balance += $validated['amount'];
        } else {
            $newBankAccount->balance -= $validated['amount'];
        }
        $newBankAccount->save();

        return redirect()->route('transactions.index')->with('success', 'อัปเดตรายการสำเร็จ');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        // Revert transaction's effect on bank account balance
        $bankAccount = BankAccount::findOrFail($transaction->bank_account_id);
        if ($transaction->type === 'income') {
            $bankAccount->balance -= $transaction->amount;
        } else {
            $bankAccount->balance += $transaction->amount;
        }
        $bankAccount->save();

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
