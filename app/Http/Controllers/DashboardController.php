<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $accounts = BankAccount::all();

        $accountLabels = $accounts->pluck('name');
        $accountBalances = $accounts->map(function ($account) {
            $income = $account->transactions()->where('type', 'income')->sum('amount');
            $expense = $account->transactions()->where('type', 'expense')->sum('amount');
            return $account->initial_balance + $income - $expense;
        });

        // รวมรายรับ-รายจ่ายทั้งหมด
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');

        // รวมรายเดือนของปีปัจจุบัน (เอาไว้ใช้กราฟรายเดือนถ้าต้องการภายหลัง)
        $monthlySummary = Transaction::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
            )
            ->whereYear('date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ข้อมูลย้อนหลัง 6 เดือน
        $monthlyData = Transaction::select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->reverse();

        return view('dashboard.index', [
            'accountLabels' => $accountLabels,
            'accountBalances' => $accountBalances,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'monthlyData' => $monthlyData
        ]);
    }
}
