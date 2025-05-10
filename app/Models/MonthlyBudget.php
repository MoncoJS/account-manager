<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyBudget extends Model
{
    protected $fillable = [
        'category_id',
        'amount',
        'month',
        'year',
        'notify_when_exceeded'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'notify_when_exceeded' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getSpentAmount()
    {
        return $this->category->transactions()
            ->where('type', 'expense')
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->sum('amount');
    }

    public function getRemainingAmount()
    {
        return $this->amount - $this->getSpentAmount();
    }

    public function isOverBudget()
    {
        return $this->getRemainingAmount() < 0;
    }

    public function getProgressPercentage()
    {
        $spent = $this->getSpentAmount();
        return min(100, ($spent / $this->amount) * 100);
    }
}
