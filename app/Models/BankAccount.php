<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankAccount extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'account_number',
        'initial_balance'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}
