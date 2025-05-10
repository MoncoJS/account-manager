<?php

namespace App\Http\Controllers;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $bankAccounts = BankAccount::all();
        return view('bank_accounts.index', compact('bankAccounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('bank_accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'account_number' => 'required',
            'initial_balance' => 'required|numeric',
        ]);

        BankAccount::create($request->all());
        return redirect()->route('bank-accounts.index')->with('success', 'เพิ่มบัญชีสำเร็จ');
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
    public function edit(BankAccount $bankAccount)
    {
        return view('bank_accounts.edit', compact('bankAccount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'initial_balance' => 'required|numeric|min:0'
        ]);

        $bankAccount->update($request->all());
        return redirect()->route('bank-accounts.index')->with('success', 'แก้ไขบัญชีสำเร็จ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();
        return redirect()->route('bank-accounts.index')->with('success', 'ลบบัญชีสำเร็จ');
    }


}
