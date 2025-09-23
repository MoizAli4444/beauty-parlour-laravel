<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'expense_type',
        'amount',
        'payment_method',
        'date',
        'receipt_path',
        'notes',
        'moderator_id',
        'moderator_type',
    ];

    /**
     * Polymorphic relation - who/what the expense was paid to (Admin, Staff, Employee, Supplier, etc.).
     */
    public function moderator()
    {
        return $this->morphTo();
    }
}
