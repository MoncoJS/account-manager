<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MonthlyBudget;
use Illuminate\Http\Request;

class MonthlyBudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('type', 'expense')->get();
        $budgets = MonthlyBudget::with('category')
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->get();
        
        return view('monthly-budgets.index', compact('categories', 'budgets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('type', 'expense')->get();
        return view('monthly-budgets.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
            'notify_when_exceeded' => 'boolean'
        ]);

        MonthlyBudget::create($validated);

        return redirect()->route('monthly-budgets.index')
            ->with('success', 'งบประมาณถูกสร้างเรียบร้อยแล้ว');
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
    public function edit(MonthlyBudget $monthlyBudget)
    {
        $categories = Category::where('type', 'expense')->get();
        return view('monthly-budgets.edit', compact('monthlyBudget', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MonthlyBudget $monthlyBudget)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
            'notify_when_exceeded' => 'boolean'
        ]);

        $monthlyBudget->update($validated);

        return redirect()->route('monthly-budgets.index')
            ->with('success', 'งบประมาณถูกอัปเดตเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MonthlyBudget $monthlyBudget)
    {
        $monthlyBudget->delete();

        return redirect()->route('monthly-budgets.index')
            ->with('success', 'งบประมาณถูกลบเรียบร้อยแล้ว');
    }

    public function checkBudgets()
    {
        $overBudgetCategories = [];
        $budgets = MonthlyBudget::with('category')
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->get();

        foreach ($budgets as $budget) {
            if ($budget->isOverBudget() && $budget->notify_when_exceeded) {
                $overBudgetCategories[] = [
                    'category' => $budget->category->name,
                    'budget' => $budget->amount,
                    'spent' => $budget->getSpentAmount(),
                    'remaining' => $budget->getRemainingAmount()
                ];
            }
        }

        return $overBudgetCategories;
    }
}
