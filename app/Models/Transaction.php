<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'bank_account_id',
        'category_id',
        'type',
        'amount',
        'date',
        'note'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date'
    ];

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // For backward compatibility
    public function getCategoryAttribute($value)
    {
        return $this->category()->first()?->name ?? $value;
    }
}
