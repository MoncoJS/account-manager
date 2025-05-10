<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'type',
        'icon',
        'color'
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function monthlyBudgets(): HasMany
    {
        return $this->hasMany(MonthlyBudget::class);
    }

    public function getCurrentMonthBudget()
    {
        return $this->monthlyBudgets()
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->first();
    }

    public function getCurrentMonthSpent()
    {
        return $this->transactions()
            ->where('type', 'expense')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
    }

    public function getCurrentMonthRemaining()
    {
        $budget = $this->getCurrentMonthBudget();
        if (!$budget) return null;
        
        return $budget->amount - $this->getCurrentMonthSpent();
    }

    public function isOverBudget()
    {
        $remaining = $this->getCurrentMonthRemaining();
        return $remaining !== null && $remaining < 0;
    }
}
